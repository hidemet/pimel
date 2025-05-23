<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {

        // Nome tabella pivot convenzionale: article_rubric (singolare, ordine alfabetico)
        Schema::create( 'article_rubric', function ( Blueprint $table ) {

            // Non è strettamente necessario un $table->id() per le tabelle pivot semplici
            // se si usa una chiave primaria composita.

            $table->foreignId( 'article_id' )
                ->constrained( 'articles' )
                ->onDelete( 'cascade' );
            // Se un articolo viene eliminato, rimuovi le sue associazioni alle rubriche

            $table->foreignId( 'rubric_id' )
                ->constrained( 'rubrics' )
                ->onDelete( 'cascade' );
            // Se una rubrica viene eliminata, rimuovi le sue associazioni agli articoli

            // Chiave primaria composita per garantire l'unicità della coppia
            // e per indicizzare correttamente la relazione.
            $table->primary( ['article_id', 'rubric_id'] );

            // Timestamps non sono solitamente necessari in una tabella pivot di questo tipo

            // a meno che tu non voglia tracciare quando un articolo è stato associato/dissociato
            // da una rubrica. Per ora, li omettiamo per semplicità.
            // $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'article_rubric' );
    }
};
