<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Content;
use App\Core\Tenancy\TenantManager;
use App\Core\Content\MetaService;

class ContentController extends Controller
{
    public function __construct(private TenantManager $tenants, private MetaService $meta) {}

    private function siteId(): int
    {
        return (int) $this->tenants->id();
    }

    public function index(Request $request, string $type)
    {
        $siteId = $this->siteId();

        $q = Content::query()
            ->where('site_id', $siteId)
            ->where('type', $type)
            ->orderByDesc('updated_at');

        if ($search = trim((string)$request->get('s', ''))) {
            $q->where(function ($w) use ($search) {
                $w->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $items = $q->paginate(15)->withQueryString();

        return view('admin.contents.index', compact('items', 'type', 'search'));
    }

    public function create(string $type)
    {
        return view('admin.contents.edit', [
            'type' => $type,
            'item' => new Content(['status' => 'draft', 'type' => $type]),
            'meta' => [],
        ]);
    }

    public function store(Request $request, string $type)
    {
        $siteId = $this->siteId();

        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255'],
            'status' => ['required','in:draft,published,trash'],
            'content' => ['nullable','string'],
            'excerpt' => ['nullable','string'],
        ]);

        $slug = trim((string)($data['slug'] ?? ''));
        if ($slug === '') {
            $slug = Str::slug($data['title']);
        }
        $slug = $this->uniqueSlug($siteId, $type, $slug);

        $item = Content::create([
            'site_id' => $siteId,
            'type' => $type,
            'status' => $data['status'],
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'] ?? null,
            'excerpt' => $data['excerpt'] ?? null,
            'published_at' => $data['status'] === 'published' ? now() : null,
        ]);

        $metaPairs = $this->extractMetaPairs($request);
        $this->meta->setMany($siteId, $item->id, $metaPairs);

        return redirect()->route('admin.contents.edit', [$type, $item->id])
            ->with('ok', 'Creado correctamente.');
    }

    public function edit(string $type, int $id)
    {
        $siteId = $this->siteId();

        $item = Content::query()
            ->where('site_id', $siteId)
            ->where('type', $type)
            ->findOrFail($id);

        $meta = $this->meta->getAll($siteId, $item->id);
        return view('admin.contents.edit', compact('type','item','meta'));
    }

    public function update(Request $request, string $type, int $id)
    {
        $siteId = $this->siteId();

        $item = Content::query()
            ->where('site_id', $siteId)
            ->where('type', $type)
            ->findOrFail($id);

        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255'],
            'status' => ['required','in:draft,published,trash'],
            'content' => ['nullable','string'],
            'excerpt' => ['nullable','string'],
        ]);

        $slug = trim((string)($data['slug'] ?? ''));
        if ($slug === '') {
            $slug = Str::slug($data['title']);
        }
        $slug = $this->uniqueSlug($siteId, $type, $slug, $item->id);

        $item->fill([
            'title' => $data['title'],
            'slug' => $slug,
            'status' => $data['status'],
            'content' => $data['content'] ?? null,
            'excerpt' => $data['excerpt'] ?? null,
            'published_at' => $data['status'] === 'published'
                ? ($item->published_at ?? now())
                : null,
        ])->save();

        $metaPairs = $this->extractMetaPairs($request);
        $this->meta->setMany($siteId, $item->id, $metaPairs);

        return back()->with('ok', 'Guardado correctamente.');
    }

    public function destroy(string $type, int $id)
    {
        $siteId = $this->siteId();

        $item = Content::query()
            ->where('site_id', $siteId)
            ->where('type', $type)
            ->findOrFail($id);

        $item->delete();

        return redirect()->route('admin.contents.index', [$type])
            ->with('ok', 'Eliminado.');
    }

    private function uniqueSlug(int $siteId, string $type, string $slug, ?int $ignoreId = null): string
    {
        $base = $slug;
        $i = 2;

        while (true) {
            $q = Content::query()
                ->where('site_id', $siteId)
                ->where('type', $type)
                ->where('slug', $slug);

            if ($ignoreId) $q->where('id', '!=', $ignoreId);

            if (!$q->exists()) return $slug;

            $slug = "{$base}-{$i}";
            $i++;
        }
    }

    private function extractMetaPairs(Request $request): array
    {
        // viene como meta_key[] y meta_value[]
        $keys = $request->input('meta_key', []);
        $vals = $request->input('meta_value', []);

        $pairs = [];
        for ($i = 0; $i < max(count($keys), count($vals)); $i++) {
            $k = trim((string)($keys[$i] ?? ''));
            $v = (string)($vals[$i] ?? '');
            if ($k === '') continue;
            $pairs[$k] = $v;
        }
        return $pairs;
    }

}
