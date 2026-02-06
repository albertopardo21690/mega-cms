<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Core\Menus\MenuService;

class MenuController extends Controller
{
    public function index()
    {
        return view('admin.menus.index');
    }

    public function edit(string $location)
    {
        $site = app('currentSite');

        $menu = Menu::firstOrCreate(
            ['site_id'=>$site->id, 'location'=>$location],
            ['name'=>ucfirst($location)]
        );

        $items = MenuItem::query()
            ->where('site_id',$site->id)
            ->where('menu_id',$menu->id)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        // padres posibles = todos menos él mismo (lo filtramos en vista por simplicidad)
        $parents = $items;

        return view('admin.menus.edit', compact('location','menu','items','parents'));
    }

    public function addItem(string $location, Request $request, MenuService $menus)
    {
        $site = app('currentSite');

        $menu = Menu::firstOrCreate(
            ['site_id'=>$site->id, 'location'=>$location],
            ['name'=>ucfirst($location)]
        );

        MenuItem::create([
            'site_id' => $site->id,
            'menu_id' => $menu->id,
            'label' => $request->string('label'),
            'url' => $request->string('url'),
            'parent_id' => $request->input('parent_id') ?: null,
            'sort' => 10,
            'is_visible' => true,
            'target' => '_self',
        ]);

        $menus->flush($site->id, $location);

        return redirect("/admin/menus/{$location}");
    }

    public function saveOrder(string $location, Request $request, MenuService $menus)
    {
        $site = app('currentSite');

        $items = (array) $request->input('items', []);
        foreach ($items as $id => $data) {
            MenuItem::query()
                ->where('site_id',$site->id)
                ->where('id',(int)$id)
                ->update([
                    'label' => $data['label'] ?? '',
                    'title' => $data['title'] ?? null,
                    'url' => $data['url'] ?? '#',
                    'sort' => (int)($data['sort'] ?? 0),
                    'parent_id' => !empty($data['parent_id']) ? (int)$data['parent_id'] : null,
                    'is_visible' => (int)($data['is_visible'] ?? 1) === 1,
                    'target' => $data['target'] ?? '_self',
                    'rel' => $data['rel'] ?? null,
                    'css_class' => $data['css_class'] ?? null,
                    'icon' => $data['icon'] ?? null,
                ]);
        }

        $menus->flush($site->id, $location);

        return redirect("/admin/menus/{$location}");
    }

    public function deleteItem(string $location, Request $request, MenuService $menus)
    {
        $site = app('currentSite');
        $id = (int) $request->input('id');

        MenuItem::query()
            ->where('site_id',$site->id)
            ->where('id',$id)
            ->delete();

        $menus->flush($site->id, $location);

        return redirect("/admin/menus/{$location}");
    }

    public function saveJson(string $location, Request $request, MenuService $menus)
    {
        $site = app('currentSite');

        $tree = $request->input('tree', []);
        if (!is_array($tree)) {
            return response()->json(['ok'=>false,'error'=>'Invalid tree'], 422);
        }

        $sortCounter = 10;

        $apply = function(array $nodes, ?int $parentId) use (&$apply, &$sortCounter, $site) {
            foreach ($nodes as $n) {
                $id = (int)($n['id'] ?? 0);
                if ($id <= 0) continue;

                \App\Models\MenuItem::query()
                    ->where('site_id', $site->id)
                    ->where('id', $id)
                    ->update([
                        'parent_id' => $parentId,
                        'sort' => $sortCounter,
                    ]);

                $sortCounter += 10;

                $children = $n['children'] ?? [];
                if (is_array($children) && count($children)) {
                    $apply($children, $id);
                }
            }
        };

        $apply($tree, null);

        $menus->flush($site->id, $location);

        return response()->json(['ok'=>true]);
    }

}
