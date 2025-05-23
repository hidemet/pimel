<?php

namespace Database\Seeders;

use App\Models\Rubric; // Importa il modello Rubric
use Illuminate\Database\Seeder;

// Non è necessario importare Str qui se lo slug è gestito dal modello

class RubricSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Opzionale: pulisci la tabella prima di popolarla.
        // Utile in sviluppo, ma da usare con cautela.
        // Rubric::truncate();

        $rubrics = [
            [
                'name'        => 'Genitorialità Consapevole',
                'description' =>

                'Strategie, riflessioni e supporto per un approccio consapevole e sereno al ruolo genitoriale.',
            ],
            [
                'name'        => 'DSA & BES',
                'description' =>

                'Approfondimenti sui Disturbi Specifici dell\'Apprendimento (DSA) e sui Bisogni Educativi Speciali (BES), con strategie di intervento e supporto.',
            ],
            [
                'name'        => 'Sviluppo 0-6 anni',
                'description' =>

                'Focus sulle tappe evolutive, sul gioco e sulle migliori pratiche educative per bambini da zero a sei anni.',
            ],
            [
                'name'        => 'Adolescenza',
                'description' =>

                'Guida per comprendere e accompagnare gli adolescenti attraverso le trasformazioni, le sfide e le scoperte di questa età.',
            ],
            [
                'name'        => 'Pedagogia & Scuola',
                'description' =>

                'Analisi, metodologie didattiche innovative e riflessioni sul sistema scolastico e sul ruolo dell\'educazione formale.',
            ],
            [
                'name'        => 'Mondo Educatori',
                'description' =>

                'Spazio dedicato a educatori e insegnanti: strumenti pratici, condivisione di esperienze e crescita professionale.',
            ],
            [
                'name'        => 'Pillole di Storia della Pedagogia',
                'description' =>

                'Viaggio attraverso le figure, le teorie e le correnti che hanno segnato la storia del pensiero pedagogico.',
            ],
            [
                'name'        => 'News Professione Pedagogica',
                'description' =>

                'Aggiornamenti su normative, eventi, formazione e dibattiti riguardanti la professione del pedagogista e dell\'educatore.',
            ],
            [
                'name'        => 'Recensioni: Libri, Film e Risorse',
                // Nome più esplicito
                'description' =>

                'Consigli di lettura, visione e utilizzo di materiali utili per la crescita personale e professionale in ambito educativo.',
            ],
            [
                'name'        => 'Pedagogia di Genere',
                'description' =>

                'Riflessioni e strumenti per un\'educazione attenta alle questioni di genere, alla parità e al superamento degli stereotipi.',
            ],
        ];

        foreach ( $rubrics as $rubricData ) {
            Rubric::create( [
                'name'        => $rubricData['name'],
                'description' => $rubricData['description'],

                // Lo slug verrà generato automaticamente dal mutator nel modello Rubric
            ] );
        }

        // Se desideri aggiungere altre rubriche generate casualmente dalla factory (oltre a queste specifiche):

        // Rubric::factory()->count(2)->create(); // Crea altre 2 rubriche fittizie
    }
}