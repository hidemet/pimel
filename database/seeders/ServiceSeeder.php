<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\TargetCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder {
    public function run(): void {
        $services = $this->loadServicesFromJson();

        if (empty($services)) {
            $this->command->error('Nessun servizio trovato nel file JSON.');
            return;
        }

        $targetCategories = $this->getTargetCategories();

        foreach ($services as $serviceData) {
            $targetCategory = $targetCategories->firstWhere('slug', $serviceData['target_category_slug']);

            if (!$targetCategory) {
                $this->command->warn("Categoria '{$serviceData['target_category_slug']}' non trovata per il servizio '{$serviceData['name']}'");
                continue;
            }

            Service::create([
                'name' => $serviceData['name'],
                'target_category_id' => $targetCategory->id,
                'description' => $serviceData['description'],
                'target_audience' => $serviceData['target_audience'],
                'objectives' => $serviceData['objectives'],
                'modalities' => $serviceData['modalities'],
            ]);
        }

        $this->command->info(count($services) . ' servizi creati con successo.');
    }

    private function loadServicesFromJson(): array {
        $filePath = database_path('data/services.json');

        if (!file_exists($filePath)) {
            $this->command->error("File JSON non trovato: {$filePath}");
            return [];
        }

        $jsonContent = file_get_contents($filePath);
        $services = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Errore nel parsing del JSON: ' . json_last_error_msg());
            return [];
        }

        return $services ?? [];
    }

    private function getTargetCategories() {
        $categories = TargetCategory::all();

        if ($categories->isEmpty()) {
            $this->command->error('Nessuna categoria target trovata. Esegui TargetCategorySeeder prima.');
            exit(1);
        }

        return $categories;
    }
}
