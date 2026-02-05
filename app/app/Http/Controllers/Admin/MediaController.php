<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Core\Tenancy\TenantManager;
use App\Models\Media;

class MediaController extends Controller
{
    public function __construct(private TenantManager $tenants) {}

    private function siteId(): int { return (int)$this->tenants->id(); }

    public function index()
    {
        $siteId = $this->siteId();

        $items = Media::query()
            ->where('site_id',$siteId)
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.media.index', compact('items'));
    }

    public function upload(Request $request)
    {
        $siteId = $this->siteId();

        $request->validate([
            'file' => ['required','file','max:8192'], // 8MB
        ]);

        $file = $request->file('file');
        $disk = 'public';

        $dir = "tenants/{$siteId}/uploads";
        $storedPath = $file->store($dir, $disk);

        Media::create([
            'site_id' => $siteId,
            'disk' => $disk,
            'path' => $storedPath,
            'filename' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'size' => (int)$file->getSize(),
            'author_id' => null,
        ]);

        return back()->with('ok','Subido correctamente.');
    }

    public function delete(Request $request)
    {
        $siteId = $this->siteId();

        $data = $request->validate(['id' => ['required','integer']]);

        $m = Media::query()->where('site_id',$siteId)->where('id',(int)$data['id'])->firstOrFail();

        Storage::disk($m->disk)->delete($m->path);
        $m->delete();

        return back()->with('ok','Eliminado.');
    }
}
