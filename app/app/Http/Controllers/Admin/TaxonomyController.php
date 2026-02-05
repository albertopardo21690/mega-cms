<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Core\Tenancy\TenantManager;
use App\Models\Taxonomy;
use App\Models\Term;

class TaxonomyController extends Controller
{
    public function __construct(private TenantManager $tenants) {}

    private function siteId(): int { return (int)$this->tenants->id(); }

    public function index()
    {
        $items = Taxonomy::query()
            ->where('site_id', $this->siteId())
            ->orderBy('taxonomy_key')
            ->get();

        return view('admin.taxonomies.index', compact('items'));
    }

    public function terms(string $taxonomyKey)
    {
        $siteId = $this->siteId();

        $taxonomy = Taxonomy::query()
            ->where('site_id', $siteId)
            ->where('taxonomy_key', $taxonomyKey)
            ->firstOrFail();

        $terms = Term::query()
            ->where('site_id', $siteId)
            ->where('taxonomy_id', $taxonomy->id)
            ->orderBy('name')
            ->get();

        return view('admin.taxonomies.terms', compact('taxonomy','terms'));
    }

    public function saveTerm(Request $request, string $taxonomyKey)
    {
        $siteId = $this->siteId();

        $taxonomy = Taxonomy::query()
            ->where('site_id', $siteId)
            ->where('taxonomy_key', $taxonomyKey)
            ->firstOrFail();

        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'slug' => ['nullable','string','max:120'],
        ]);

        $slug = trim((string)($data['slug'] ?? ''));
        if ($slug === '') $slug = Str::slug($data['name']);

        // slug único por taxonomy
        $base = $slug; $i = 2;
        while (
            Term::query()
                ->where('site_id',$siteId)
                ->where('taxonomy_id',$taxonomy->id)
                ->where('slug',$slug)
                ->exists()
        ) { $slug = "{$base}-{$i}"; $i++; }

        Term::create([
            'site_id' => $siteId,
            'taxonomy_id' => $taxonomy->id,
            'name' => $data['name'],
            'slug' => $slug,
        ]);

        return back()->with('ok','Término creado.');
    }

    public function deleteTerm(Request $request, string $taxonomyKey)
    {
        $siteId = $this->siteId();

        $taxonomy = Taxonomy::query()
            ->where('site_id', $siteId)
            ->where('taxonomy_key', $taxonomyKey)
            ->firstOrFail();

        $data = $request->validate([
            'term_id' => ['required','integer'],
        ]);

        Term::query()
            ->where('site_id', $siteId)
            ->where('taxonomy_id', $taxonomy->id)
            ->where('id', (int)$data['term_id'])
            ->delete();

        return back()->with('ok','Término eliminado.');
    }
}
