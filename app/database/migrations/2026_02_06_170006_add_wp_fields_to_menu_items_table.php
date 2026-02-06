<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            if (!Schema::hasColumn('menu_items','title')) $table->string('title', 190)->nullable()->after('label');
            if (!Schema::hasColumn('menu_items','target')) $table->string('target', 20)->default('_self')->after('url');
            if (!Schema::hasColumn('menu_items','rel')) $table->string('rel', 190)->nullable()->after('target');
            if (!Schema::hasColumn('menu_items','css_class')) $table->string('css_class', 190)->nullable()->after('rel');
            if (!Schema::hasColumn('menu_items','icon')) $table->string('icon', 60)->nullable()->after('css_class');
            if (!Schema::hasColumn('menu_items','type')) $table->string('type', 30)->default('custom')->after('icon');
            if (!Schema::hasColumn('menu_items','ref')) $table->string('ref', 120)->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            foreach (['title','target','rel','css_class','icon','type','ref'] as $col) {
                if (Schema::hasColumn('menu_items',$col)) $table->dropColumn($col);
            }
        });
    }
};
