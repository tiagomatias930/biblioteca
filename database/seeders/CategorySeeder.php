<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Certificações', 'slug' => 'documentos-certificacao', 'sort_order' => 1, 'is_protected' => false],
            ['name' => 'Documentos Pessoais', 'sort_order' => 2, 'is_protected' => false],
            ['name' => 'Documentos para Vaga de Emprego', 'sort_order' => 3, 'is_protected' => false],
            ['name' => 'Documentos Confidenciais', 'sort_order' => 4, 'is_protected' => true, 'access_code' => config('services.category.confidential_access_code')],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
