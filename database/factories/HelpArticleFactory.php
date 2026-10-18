<?php

namespace Database\Factories;

use App\Models\HelpArticle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Tenant is assigned from CurrentTenant when the article is created.
 *
 * @extends Factory<HelpArticle>
 */
class HelpArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(5),
            'body' => fake()->paragraphs(3, true),
        ];
    }
}
