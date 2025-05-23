<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'contact_messages', function ( Blueprint $table ) {
            $table->id();
            $table->string( 'name' );
            $table->string( 'email' );
            $table->string( 'subject' )->nullable();
            $table->text( 'message' );
            $table->string( 'service_of_interest' )->nullable();
            // Potrebbe contenere l'ID o lo slug del servizio
            $table->boolean( 'is_read' )->default( false );
            // Flag per l'admin per segnare come letto
            $table->timestamp( 'archived_at' )->nullable();
            // Per archiviare messaggi senza eliminarli
            $table->timestamps();
            // created_at (quando il messaggio è stato inviato) e updated_at
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'contact_messages' );
    }
};
