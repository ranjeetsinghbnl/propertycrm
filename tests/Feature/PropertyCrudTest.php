<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class PropertyCrudTest extends TestCase
{
    use DatabaseMigrations;
    private $property1 = 'd8d9c545-3832-3d85-a25a-aac181479be7';
    private $property2 = 'd8d9c545-3832-3d85-a25a-aac181479be9';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' =>  Hash::make('password')
        ]);

        $this->time = now();

        PropertyType::factory()->createMany([
            [
                'ex_property_type_id' => 1,
                'title' => 'Flat',
                'description' => 'rom studio flats, to maisonettes and 2-storey flat',
                'source' => 'api',
                'type_created_at' => '2020-01-08 12:55:35',
                'type_updated_at' => '2020-01-08 12:55:35'
            ],
            [
                'ex_property_type_id' => 2,
                'title' => 'Semi-detached',
                'description' => 'Semi-detached properties are a lot more common',
                'source' => 'api',
                'type_created_at' => '2020-01-08 12:55:35',
                'type_updated_at' => '2020-01-08 12:55:35'
            ]
        ]);
        Property::factory()->createMany([
            [
                'ex_property_id' => $this->property1,
                'county' => 'Pennsylvania',
                'country' => 'Cocos (Keeling) Islands',
                'town' => 'Johnsonbury',
                'description' => 'Debitis doloribus eos optio debitis. Et accusamus exercitationem blanditiis enim fuga optio vitae. Eligendi temporibus cum blanditiis. Voluptate culpa quos reiciendis doloremque officiis.',
                'address' => '6446 Smitham Ferry Apt. 571',
                'image_full' => 'https://p-hold.com/1000/400?57108',
                'image_thumbnail' => 'https://p-hold.com/100/100?54016',
                'latitude' => -64.30492900,
                'longitude' => -154.21992800,
                'num_bedrooms' => 2,
                'num_bathrooms' => 2,
                'price' => 85352.00,
                'property_type_id' => 1,
                'type' => 'sale',
                'source' => 'api',
                'property_created_at' => '2020-01-08 12:55:37',
                'property_updated_at' => '2020-01-08 12:55:37',
                'created_at' => $this->time,
                'zip' => '145101'
            ],
            [
                'ex_property_id' => $this->property2,
                'county' => 'Delaware',
                'country' => 'Guernsey',
                'town' => 'South Katelyn',
                'description' => 'In cupiditate fuga et in totam. Enim aut vel atque consequatur et et. Et aperiam eum aliquid rerum facilis ex. Vel maxime reprehenderit ea illum et eos.',
                'address' => '23663 Oberbrunner Bridge Suite 239',
                'image_full' => 'https://p-hold.com/1000/400?49440',
                'image_thumbnail' => 'https://p-hold.com/100/100?62816',
                'latitude' => -8.89299000,
                'longitude' => -50.37355600,
                'num_bedrooms' => 3,
                'num_bathrooms' => 2,
                'price' => 1565191.00,
                'property_type_id' => 2,
                'type' => 'rent',
                'source' => 'api',
                'property_created_at' => '2020-01-08 12:55:37',
                'property_updated_at' => '2020-01-08 12:55:37',
                'created_at' => $this->time,
                'zip' => '145101'
            ]
        ]);
    }

    public function test_can_view_properties()
    {
        $this->actingAs($this->user)
            ->get('/properties')
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Properties/Index')
                    ->has('properties.data', 2)
                    ->has(
                        'properties.data.0',
                        fn (Assert $page) => $page
                            ->where('id', 1)
                            ->where('county', 'Pennsylvania')
                            ->where('country', 'Cocos (Keeling) Islands')
                            ->where('type_details', 'Flat')
                            ->where("id", 1)
                            ->where("ex_property_id", $this->property1)
                            ->where("county", "Pennsylvania")
                            ->where("country", "Cocos (Keeling) Islands")
                            ->where("town", "Johnsonbury")
                            ->where("description", "Debitis doloribus eos optio debitis. Et accusamus exercitationem blanditiis enim fuga optio vitae. Eligendi temporibus cum blanditiis. Voluptate culpa quos reiciendis doloremque officiis.")
                            ->where("address", "6446 Smitham Ferry Apt. 571")
                            ->where("image_full", "https://p-hold.com/1000/400?57108")
                            ->where("image_thumbnail", "https://p-hold.com/100/100?54016")
                            ->where("latitude", -64.304929)
                            ->where("longitude", -154.219928)
                            ->where("num_bedrooms", 2)
                            ->where("num_bathrooms", 2)
                            ->where("price", 85352)
                            ->where("property_type_id", 1)
                            ->where("type", "sale")
                            ->where("property_created_at", "2020-01-08 12:55:37")
                            ->where("property_updated_at", "2020-01-08 12:55:37")
                            ->where("source", "api")
                            ->where("zip", "145101")
                    )
                    ->has(
                        'properties.data.1',
                        fn (Assert $page) =>
                        $page
                            ->where("id", 2)
                            ->where("ex_property_id", $this->property2)
                            ->where("county", "Delaware")
                            ->where("country", "Guernsey")
                            ->where("town", "South Katelyn")
                            ->where("description", "In cupiditate fuga et in totam. Enim aut vel atque consequatur et et. Et aperiam eum aliquid rerum facilis ex. Vel maxime reprehenderit ea illum et eos.")
                            ->where("address", "23663 Oberbrunner Bridge Suite 239")
                            ->where("image_full", "https://p-hold.com/1000/400?49440")
                            ->where("image_thumbnail", "https://p-hold.com/100/100?62816")
                            ->where("latitude", -8.89299)
                            ->where("longitude", -50.373556)
                            ->where("num_bedrooms", 3)
                            ->where("num_bathrooms", 2)
                            ->where("price", 1565191)
                            ->where("property_type_id", 2)
                            ->where("type", "rent")
                            ->where("property_created_at", "2020-01-08 12:55:37")
                            ->where("property_updated_at", "2020-01-08 12:55:37")
                            ->where("source", "api")
                            ->where("zip", "145101")
                            ->where("type_details", "Semi-detached")
                    )
            );
    }

    public function test_can_search_for_properties()
    {
        $this->actingAs($this->user)
            ->get('/properties?search=Delaware')
            ->assertInertia(
                fn (Assert $assert) => $assert
                    ->component('Properties/Index')
                    ->where('filters.search', 'Delaware')
                    ->has('properties.data', 1)
                    ->has(
                        'properties.data.0',
                        fn (Assert $page) =>
                        $page
                            ->where("id", 2)
                            ->where("ex_property_id", $this->property2)
                            ->where("county", "Delaware")
                            ->where("country", "Guernsey")
                            ->where("town", "South Katelyn")
                            ->where("description", "In cupiditate fuga et in totam. Enim aut vel atque consequatur et et. Et aperiam eum aliquid rerum facilis ex. Vel maxime reprehenderit ea illum et eos.")
                            ->where("address", "23663 Oberbrunner Bridge Suite 239")
                            ->where("image_full", "https://p-hold.com/1000/400?49440")
                            ->where("image_thumbnail", "https://p-hold.com/100/100?62816")
                            ->where("latitude", -8.89299)
                            ->where("longitude", -50.373556)
                            ->where("num_bedrooms", 3)
                            ->where("num_bathrooms", 2)
                            ->where("price", 1565191)
                            ->where("property_type_id", 2)
                            ->where("type", "rent")
                            ->where("property_created_at", "2020-01-08 12:55:37")
                            ->where("property_updated_at", "2020-01-08 12:55:37")
                            ->where("source", "api")
                            ->where("zip", "145101")
                            ->where("type_details", "Semi-detached")
                    )
            );
    }


    public function test_can_render_create_property()
    {
        $this->actingAs($this->user)
            ->get('/properties/create')
            ->assertInertia(
                fn (Assert $assert) => $assert
                    ->component('Properties/Create')
                    ->has('propertyTypes', 2)
                    ->has('saleType', 2)
            );
    }


    public function test_can_render_edit_property()
    {
        $this->actingAs($this->user)
            ->get('/properties/1/edit')
            ->assertInertia(
                fn (Assert $assert) => $assert
                    ->component('Properties/Edit')
                    ->has('propertyTypes', 2)
                    ->has('saleType', 2)
                    ->has(
                        'property',
                        fn (Assert $page) => $page
                            ->where("id", 1)
                            ->where("county", "Pennsylvania")
                            ->where("country", "Cocos (Keeling) Islands")
                            ->where("town", "Johnsonbury")
                            ->where("zip", "145101")
                            ->where("description", "Debitis doloribus eos optio debitis. Et accusamus exercitationem blanditiis enim fuga optio vitae. Eligendi temporibus cum blanditiis. Voluptate culpa quos reiciendis doloremque officiis.")
                            ->where("address", "6446 Smitham Ferry Apt. 571")
                            ->where("num_bathrooms", 2)
                            ->where("num_bedrooms", 2)
                            ->where("price", 85352)
                            ->where("property_type_id", 1)
                            ->where("type", "sale")
                            ->where("source", "api")
                            ->where("longitude", -154.219928)
                            ->where("latitude", -64.304929)
                            ->where("image_full", "https://p-hold.com/1000/400?57108")
                            ->where("image_thumbnail", "https://p-hold.com/100/100?54016")
                            ->where("image_full_preview", "http://propertycrm.test/img/https://p-hold.com/1000/400?57108")
                            ->where("image_thumbnail_preview", "http://propertycrm.test/img/https://p-hold.com/1000/400?57108?w=200&h=200&fit=crop")
                    )
            );
    }
}
