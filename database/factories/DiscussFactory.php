<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class DiscussFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    // DiscussFactory unaset sheria na kwenye database seeder unatengeneze text awe anamiliki
    // user mwenye 'user_id' => '1'
    public function definition(): array
    {
        return [
            'text' => fake()->text(),
        ];
    }

}
