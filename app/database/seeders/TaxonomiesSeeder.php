<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Site;
use App\Models\Taxonomy;

class TaxonomiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Site::all() as $site) {
            Taxonomy::updateOrCreate(
                ['site_id' => $site->id, 'taxonomy_key' => 'category'],
                ['label' => 'Categorías']
            );

            Taxonomy::updateOrCreate(
                ['site_id' => $site->id, 'taxonomy_key' => 'tag'],
                ['label' => 'Etiquetas']
            );
        }
    }
}
