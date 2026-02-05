<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Core\Tenancy\TenantManager;
use App\Core\Settings\SettingsService;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function __construct(
        private TenantManager $tenants,
        private SettingsService $settings
    ) {}

    private function siteId(): int
    {
        return (int)$this->tenants->id();
    }

    public function index()
    {
        $siteId = $this->siteId();

        $items = Setting::query()
            ->where('site_id', $siteId)
            ->orderBy('autoload', 'desc')
            ->orderBy('key')
            ->get();

        return view('admin.settings.index', compact('items'));
    }

    public function save(Request $request)
    {
        $siteId = $this->siteId();

        $data = $request->validate([
            'key' => ['required','string','max:120'],
            'value' => ['nullable','string'],
            'autoload' => ['nullable'],
        ]);

        $this->settings->set(
            $siteId,
            trim($data['key']),
            $data['value'] ?? null,
            (bool)($data['autoload'] ?? false)
        );

        return back()->with('ok', 'Setting guardado.');
    }

    public function delete(Request $request)
    {
        $siteId = $this->siteId();

        $data = $request->validate([
            'key' => ['required','string','max:120'],
        ]);

        $this->settings->forget($siteId, trim($data['key']));

        return back()->with('ok', 'Setting eliminado.');
    }

    public function flush()
    {
        $siteId = $this->siteId();
        $this->settings->flush($siteId);

        return back()->with('ok', 'Caché de settings vaciada.');
    }
}
