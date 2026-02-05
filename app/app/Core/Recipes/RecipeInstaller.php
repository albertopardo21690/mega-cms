<?php

namespace App\Core\Recipes;

use App\Models\Site;
use App\Models\Content;
use App\Models\ContentType;
use App\Core\Settings\SettingsService;
use Illuminate\Support\Str;

class RecipeInstaller
{
    public function __construct(private SettingsService $settings) {}

    public function install(Site $site, string $recipe): void
    {
        match ($recipe) {
            'corporate' => $this->installCorporate($site),
            'blog'      => $this->installBlog($site),
            default     => null,
        };
    }

    private function ensureType(Site $site, string $key, string $label): void
    {
        ContentType::updateOrCreate(
            ['site_id' => $site->id, 'type_key' => $key],
            ['label' => $label]
        );
    }

    private function installCorporate(Site $site): void
    {
        $this->ensureType($site, 'page', 'Páginas');

        // Home
        Content::updateOrCreate(
            ['site_id'=>$site->id,'type'=>'page','slug'=>'home'],
            [
                'status'=>'published',
                'title'=>'Inicio',
                'content'=>'<p>Bienvenido a nuestro sitio corporativo.</p>',
                'published_at'=>now()
            ]
        );

        // Sobre nosotros
        Content::updateOrCreate(
            ['site_id'=>$site->id,'type'=>'page','slug'=>'sobre-nosotros'],
            [
                'status'=>'published',
                'title'=>'Sobre Nosotros',
                'content'=>'<p>Quiénes somos…</p>',
                'published_at'=>now()
            ]
        );

        // Contacto
        Content::updateOrCreate(
            ['site_id'=>$site->id,'type'=>'page','slug'=>'contacto'],
            [
                'status'=>'published',
                'title'=>'Contacto',
                'content'=>'<p>Formulario / datos de contacto…</p>',
                'published_at'=>now()
            ]
        );

        // Settings base
        $this->settings->set($site->id, 'site_tagline', 'Soluciones profesionales', true);
        $this->settings->set($site->id, 'front_page', 'home', true);
    }

    private function installBlog(Site $site): void
    {
        $this->ensureType($site, 'post', 'Entradas');

        // Post demo
        Content::updateOrCreate(
            ['site_id'=>$site->id,'type'=>'post','slug'=>'primer-post'],
            [
                'status'=>'published',
                'title'=>'Primer Post',
                'excerpt'=>'Este es el primer post del blog.',
                'content'=>'<p>Contenido de ejemplo…</p>',
                'published_at'=>now()
            ]
        );

        $this->settings->set($site->id, 'blog_enabled', '1', true);
    }
}
