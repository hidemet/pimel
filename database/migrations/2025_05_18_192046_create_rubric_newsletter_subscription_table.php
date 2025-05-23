<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'rubric_newsletter_subscription', function ( Blueprint
             $table ) {
            // Definisci le colonne prima
            $table->unsignedBigInteger( 'newsletter_subscription_id' );
            $table->unsignedBigInteger( 'rubric_id' );

            // Poi definisci le chiavi esterne con nomi personalizzati
            $table->foreign( 'newsletter_subscription_id',
                'rns_newsletter_sub_fk' ) // Nome personalizzato per il vincolo
                ->references( 'id' )
                ->on( 'newsletter_subscriptions' )
                ->onDelete( 'cascade' );

            $table->foreign( 'rubric_id', 'rns_rubric_fk' )
            // Nome personalizzato per il vincolo
                ->references( 'id' )
                ->on( 'rubrics' )
                ->onDelete( 'cascade' );

            $table->primary( ['newsletter_subscription_id', 'rubric_id'] );
            $table->timestamps(); // Opzionale
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table( 'rubric_newsletter_subscription', function ( Blueprint
             $table ) {

            // È buona pratica fare il drop dei vincoli esplicitamente nel down se li hai nominati
            $table->dropForeign( 'rns_newsletter_sub_fk' );
            $table->dropForeign( 'rns_rubric_fk' );
        } );
        Schema::dropIfExists( 'rubric_newsletter_subscription' );
    }
};