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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id')->index();

            $table->string('type')->index(); // page, post, product...
            $table->string('status')->default('draft')->index(); // draft, published, trash
            $table->string('title')->default('');
            $table->string('slug')->index();
            $table->longText('content')->nullable();
            $table->longText('excerpt')->nullable();

            $table->unsignedBigInteger('author_id')->nullable()->index();
            $table->unsignedBigInteger('parent_id')->nullable()->index();

            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['site_id', 'type', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
