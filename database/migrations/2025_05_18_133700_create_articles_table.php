<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'articles', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'user_id' )->constrained()->cascadeOnDelete();
            $table->string( 'title' );
            $table->string( 'slug' )->unique();
            $table->text( 'excerpt' )->nullable();
            $table->longText( 'body' );
            $table->string( 'image_path' )->nullable();
            $table->timestamp( 'published_at' )->nullable();
            $table->integer( 'reading_time' )->nullable()->unsigned();
            $table->string( 'status' )->default( 'draft' );
            // 'draft', 'published', 'archived'
            $table->string( 'meta_description', 500 )->nullable();
            $table->string( 'meta_keywords' )->nullable();
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'articles' );
    }
};
