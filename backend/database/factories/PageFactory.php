<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3, false);

        return [
            'menu_item_id' => MenuItem::factory(),
            'title'        => $title,
            'slug'         => Str::slug($title),
            'body'         => fake()->paragraphs(3, true),
            'cover_image'  => null,
            'status'       => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function published(): static
    {
        return $this->state(['status' => 'published']);
    }
}
