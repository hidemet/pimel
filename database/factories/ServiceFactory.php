// database/factories/ServiceFactory.php
<?php

namespace Database\Factories;

use App\Models\Service; // Importa il modello Service
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // Per lo slug, sebbene il modello lo gestisca

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(rand(3, 6), true); // Nome del servizio
        $name = Str::title($name);

        return [
            'name' => $name,
            // Lo slug è gestito dal mutator nel modello Service se vuoto
            // 'slug' => Str::slug($name),
            'description' => fake()->paragraph(rand(3, 5)), // Descrizione più lunga
            'target_audience' => fake()->sentence(rand(8, 15)), // A chi si rivolge
            'objectives' => $this->generateListItems(rand(3, 5)), // Elenco puntato per gli obiettivi
            'modalities' => $this->generateListItems(rand(2, 4)), // Elenco puntato per le modalità
            'is_active' => fake()->boolean(80), // 80% di probabilità che sia attivo
        ];
    }

    /**
     * Helper function to generate a string formatted as list items.
     * Questo è solo un esempio, puoi strutturare questi campi come testo semplice
     * o JSON se preferisci una struttura più definita.
     * Per ora, creeremo un semplice testo con "pseudo" elenchi.
     */
    private function generateListItems(int $count): string
    {
        $items = [];
        for ($i = 0; $i < $count; $i++) {
            $items[] = '- ' . fake()->sentence(rand(5, 10));
        }
        return implode("\n", $items); // Restituisce una stringa con ogni item su una nuova riga
    }
}