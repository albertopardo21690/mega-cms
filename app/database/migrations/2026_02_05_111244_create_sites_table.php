<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('subdomain')->unique();     // cliente1, cliente2...
            $table->string('domain')->nullable();      // futuro: dominio personalizado
            $table->string('name');
            $table->string('locale')->default('es');
            $table->string('timezone')->default('Europe/Madrid');
            $table->string('theme')->default('default');
            $table->json('modules')->nullable();       // lista de módulos activos por site
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['subdomain', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
