<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Site;

class SitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Site::updateOrCreate(
            ['subdomain' => 'cliente1'],
            ['name' => 'Cliente 1', 'theme' => 'default', 'modules' => ['Core']]
        );

        Site::updateOrCreate(
            ['subdomain' => 'cliente2'],
            ['name' => 'Cliente 2', 'theme' => 'default', 'modules' => ['Core']]
        );
    }
}
