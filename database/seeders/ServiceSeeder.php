<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\TargetCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = $this->loadServicesFromJson();
        $targetCategories = TargetCategory::all();

        foreach ($services as $serviceData) {
            $targetCategory = $targetCategories->firstWhere('slug', $serviceData['target_category_slug']);

            if (!$targetCategory) {
                continue;
            }

            Service::create([
                'name' => $serviceData['name'],
                'description' => $serviceData['description'],
                'target_audience' => $serviceData['target_audience'],
                'objectives' => $serviceData['objectives'],
                'modalities' => $serviceData['modalities'],
                'is_active' => $serviceData['is_active'],
                'target_category_id' => $targetCategory->id,
            ]);
        }

        $this->command->info('Servizi creati con successo.');
    }

    private function loadServicesFromJson(): array
    {
        $filePath = database_path('data/services.json');
        $jsonContent = file_get_contents($filePath);
        return json_decode($jsonContent, true) ?? [];
    }
}
