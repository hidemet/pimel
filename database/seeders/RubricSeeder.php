<?php

namespace Database\Seeders;

use App\Models\Rubric;
use Illuminate\Database\Seeder;

class RubricSeeder extends Seeder
{
    public function run(): void
    {
        $rubrics = $this->loadRubricsFromJson();

        foreach ($rubrics as $rubricData) {
            Rubric::create([
                'name' => $rubricData['name'],
                'description' => $rubricData['description'],
            ]);
        }

        $this->command->info('Rubriche create con successo.');
    }

    private function loadRubricsFromJson(): array
    {
        $filePath = database_path('data/rubrics.json');
        $jsonContent = file_get_contents($filePath);
        return json_decode($jsonContent, true) ?? [];
    }
}
