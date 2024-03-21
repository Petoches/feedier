<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->text(),
            'user_id' => User::factory(),
            'email' => function (array $attributes) {
                return User::find($attributes['user_id'])->email;
            },
            'source' => \App\Enums\FeedbackSource::Dashboard->name,
        ];
    }

    public function fromExternal(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'source' => \App\Enums\FeedbackSource::External->name,
            ];
        });
    }

    public function withoutUser(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email' => $this->faker->safeEmail(),
                'user_id' => null,
            ];
        });
    }

    public function deleted(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'deleted_at' => now(),
            ];
        });
    }
}
