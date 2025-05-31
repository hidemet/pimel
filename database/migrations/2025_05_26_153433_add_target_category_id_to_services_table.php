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
        Schema::table('services', function (Blueprint $table) {
            // Aggiungi la colonna per la chiave esterna
            // Rendiamola nullable se un servizio può non avere una categoria specifica
            $table->foreignId('target_category_id')
                  ->nullable() // Permetti che sia NULL
                  ->after('modalities') // Posiziona la colonna dopo 'modalities' (opzionale)
                  ->constrained('target_categories') // Crea il vincolo di chiave esterna verso la tabella 'target_categories'
                  ->onDelete('set null'); // Se una categoria viene eliminata, imposta target_category_id a NULL nei servizi associati
                                          // Altre opzioni: ->onDelete('cascade') per eliminare anche i servizi (probabilmente non desiderato)
                                          // Oppure ->onDelete('restrict') per impedire l'eliminazione della categoria se ha servizi associati.
                                          // 'set null' sembra una scelta ragionevole qui.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Rimuovi prima il vincolo di chiave esterna
            // Il nome del vincolo è generato da Laravel come: nomeTabella_nomeColonna_foreign
            $table->dropForeign(['target_category_id']); // Alternativa: $table->dropForeign('services_target_category_id_foreign');
            // Poi rimuovi la colonna
            $table->dropColumn('target_category_id');
        });
    }
};