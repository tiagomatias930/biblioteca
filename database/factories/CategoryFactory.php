<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->words(2, true)),
            'description' => $this->faker->sentence(),
            'is_protected' => false,
            'access_code' => null,
            'sort_order' => 0,
        ];
    }

    public function protected(string $code = 'segredo123'): static
    {
        return $this->state(fn () => [
            'is_protected' => true,
            'access_code' => $code,
        ]);
    }
}
