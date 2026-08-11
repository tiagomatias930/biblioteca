<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'title' => ucfirst($this->faker->words(3, true)),
            'disk_path' => 'documents/test/' . $this->faker->uuid() . '.pdf',
            'original_name' => $this->faker->word() . '.pdf',
            'mime_type' => 'application/pdf',
            'size' => $this->faker->numberBetween(1024, 5_000_000),
        ];
    }
}
