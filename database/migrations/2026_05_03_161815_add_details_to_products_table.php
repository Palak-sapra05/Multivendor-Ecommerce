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
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->nullable()->after('store_id');
            $table->string('brand')->nullable()->after('name');
            $table->json('sizes')->nullable()->after('description');
            $table->json('colors')->nullable()->after('sizes');
            $table->json('specifications')->nullable()->after('colors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['category', 'brand', 'sizes', 'colors', 'specifications']);
        });
    }
};
