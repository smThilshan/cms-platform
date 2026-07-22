<?php

namespace Database\Factories;

use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MenuItem>
 */
class MenuItemFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(2, true);

        return [
            'title'     => ucfirst($title),
            'slug'      => Str::slug($title),
            'parent_id' => null,
            'order'     => fake()->numberBetween(0, 10),
        ];
    }
}
