<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyType>
 */
class PropertyTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ex_property_type_id' => $this->faker->randomNumber(1),
            'title' => $this->faker->realText(10),
            'description' => $this->faker->text(50),
            'source' => 'api',
            'type_created_at' => now(),
            'type_updated_at' => now()
        ];
    }
}
