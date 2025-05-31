<?php

namespace Database\Seeders;

use App\Models\TargetCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TargetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // TargetCategory::truncate(); // Opzionale, se vuoi pulire la tabella prima

        $categories = [
            [
                'name' => 'Genitori',
                'icon_class' => 'family_restroom',
                'description' => 'Servizi dedicati a mamme, papà e figure genitoriali.',
            ],
            [
                'name' => 'Professionisti',
                'icon_class' => 'school', // Era 'work', l'abbiamo cambiato in 'school' per coerenza con una tua precedente richiesta, ma puoi rimettere 'work' o altro
                'description' => 'Servizi per educatori, insegnanti, pedagogisti.',
            ],
            [
                'name' => 'Scuole',
                'icon_class' => 'corporate_fare',
                'description' => 'Servizi rivolti a istituzioni scolastiche ed educative.',
            ],
            [
                'name' => 'Studenti',
                'icon_class' => 'sentiment_calm',
                'description' => 'Servizi di supporto allo studio e orientamento.',
            ],
            [
                'name' => 'Altri Servizi',
                'icon_class' => 'auto_awesome_motion',
                'description' => 'Servizi speciali o percorsi non categorizzati.',
            ],
        ];

        foreach ($categories as $categoryData) {
            TargetCategory::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']), // Genera slug dal nome
                'icon_class' => $categoryData['icon_class'],
                'description' => $categoryData['description'],
            ]);
        }

        $this->command->info(count($categories) . ' categorie di target create.');
    }
}