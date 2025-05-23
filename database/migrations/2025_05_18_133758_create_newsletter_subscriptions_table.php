<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'newsletter_subscriptions', function ( Blueprint $table ) {
            $table->id(); // PK, auto-increment, bigInteger
            $table->string( 'email' )->unique();
            // L'indirizzo email deve essere unico
            $table->string( 'token' )->unique()->nullable();
            // Token per double opt-in o link di disiscrizione
            $table->timestamp( 'confirmed_at' )->nullable();
            // Data e ora in cui l'iscrizione è stata confermata
            $table->timestamps();
            // created_at (quando è stata richiesta l'iscrizione) e updated_at
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'newsletter_subscriptions' );
    }
};