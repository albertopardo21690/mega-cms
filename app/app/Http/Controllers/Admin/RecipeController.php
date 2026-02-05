<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Core\Tenancy\TenantManager;
use App\Core\Recipes\RecipeInstaller;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function __construct(
        private TenantManager $tenants,
        private RecipeInstaller $recipes
    ) {}

    public function index()
    {
        return view('admin.recipes.index');
    }

    public function install(Request $request)
    {
        $data = $request->validate([
            'recipe' => ['required','in:corporate,blog']
        ]);

        $site = app('currentSite');
        $this->recipes->install($site, $data['recipe']);

        return back()->with('ok','Receta instalada.');
    }
}
