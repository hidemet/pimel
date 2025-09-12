<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('target_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('icon_class')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Aggiungo il foreign key alla tabella services dopo che target_categories esiste
        Schema::table('services', function (Blueprint $table) {
            $table->foreign('target_category_id')->references('id')->on('target_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['target_category_id']);
        });

        Schema::dropIfExists('target_categories');
    }
};
