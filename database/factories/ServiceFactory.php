<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\TargetCategory; // Importa il modello TargetCategory
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(rand(3, 6), true);
        $name = Str::title($name);

        // Prendi un ID di categoria target casuale o null
        $targetCategoryId = null;
        if (TargetCategory::count() > 0) {
            // 80% di probabilità di avere una categoria, 20% di essere null (non categorizzato)
            // Oppure assegna sempre una categoria se preferisci
            if (fake()->boolean(80)) {
                $targetCategoryId = TargetCategory::inRandomOrder()->first()->id;
            }
        }


        return [
            'name' => $name,
            'description' => fake()->paragraph(rand(3, 5)),
            'target_audience' => fake()->sentence(rand(8, 15)), // Questo campo potrebbe essere meno rilevante ora o usato per dettagli specifici
            'objectives' => $this->generateListItems(rand(3, 5)),
            'modalities' => $this->generateListItems(rand(2, 4)),
            'is_active' => fake()->boolean(90), // 90% attivo
            'target_category_id' => $targetCategoryId, // Assegna la categoria
        ];
    }

    private function generateListItems(int $count): string
    {
        $items = [];
        for ($i = 0; $i < $count; $i++) {
            $items[] = '- ' . fake()->sentence(rand(5, 10));
        }
        return implode("\n", $items);
    }

    // Potresti aggiungere uno state per assegnare una categoria specifica
    public function forCategory(TargetCategory $category): static
    {
        return $this->state(fn (array $attributes) => [
            'target_category_id' => $category->id,
        ]);
    }
}