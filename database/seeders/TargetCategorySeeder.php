<?php

namespace Database\Seeders;

use App\Models\TargetCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TargetCategorySeeder extends Seeder {
    public function run(): void {
        $categories = $this->loadCategoriesFromJson();

        foreach ($categories as $categoryData) {
            TargetCategory::updateOrCreate(
                ['slug' => Str::slug($categoryData['name'])],
                $categoryData
            );
        }

        $this->command->info('Categorie target create con successo.');
    }

    private function loadCategoriesFromJson(): array {
        $filePath = database_path('data/target_categories.json');
        $jsonContent = file_get_contents($filePath);
        return json_decode($jsonContent, true) ?? [];
    }
}
