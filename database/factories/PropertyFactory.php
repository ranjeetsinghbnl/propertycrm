<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ex_property_id' => $this->faker->uuid(),
            'county' => $this->faker->streetAddress,
            'country' => $this->faker->country,
            'town' => $this->faker->streetAddress,
            'description' => $this->faker->realText(50),
            'address' => $this->faker->address(),
            'image_full' => 'https://p-hold.com/1000/400?57108',
            'image_thumbnail' => 'https://p-hold.com/100/100?54016',
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'num_bedrooms' => 2,
            'num_bathrooms' => 2,
            'price' => $this->faker->randomFloat(2),
            'property_type_id' => 1,
            'type' => 'sale',
            'source' => 'api',
            'property_created_at' => now(),
            'property_updated_at' => now(),
            'zip' => $this->faker->postcode()
        ];
    }
}
