<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Property;
use App\Models\PropertyType;
//use Illuminate\Support\Facades\DB;

class CraigPropertyService
{
    public function handleCraigProperties()
    {
        // iterate 1 times
        Log::info("Started syncing properties from third party");
        try {
            $records = $this->getProperties();
            if ($records && $records['data']) {
                Log::info("First iteration - third party property sync done");
                Log::info("First iteration - stats", [
                    'current_page' => $records['current_page'],
                    'total' => $records['total'],
                    'from' => $records['from'],
                    'to' => $records['to']
                ]);
                // upsert properties
                $this->synInProperties(is_array($records['data']) ? $records['data'] : []);

                // handle pagination
                $totalPages = $records['last_page'];
                for ($i = 2; $i <= $totalPages; $i++) {
                    $records = $this->getProperties($i);
                    if ($records && $records['data']) {
                        // upsert properties
                        Log::info("$i iteration - stats", [
                            'current_page' => $records['current_page'],
                            'total' => $records['total'],
                            'from' => $records['from'],
                            'to' => $records['to']
                        ]);
                        $this->synInProperties(is_array($records['data']) ? $records['data'] : []);
                        // For testing
                        // if ($i == 3) {
                        //     throw new \Exception('Custom Exception message');
                        // }
                    }
                }
            } else {
                Log::info("Empty data - third party property sync");
            }
        } catch (\Throwable $e) {
            report($e);
            Log::info("Syncing properties error occurred");
            return false;
        }
        Log::info("End syncing properties from third party");
        // return response()->json([
        //     'ok' => true
        // ]);
    }


    private function getProperties($page = 1, $limit = 100)
    {
        $response = Http::get(env('CRAIG_PROPERTY_API_URL') . "?api_key=" . env('CRAIG_API_KEY') . "&page[number]=$page&page[size]=$limit");
        if ($response->ok()) {
            Log::info("success in iteration from third party API", ['page' => $page, 'limit' => $limit]);
            return $response->json();
        } else {
            Log::info("error in iteration from third party API", ['page' => $page, 'limit' => $limit]);
            return [];
            // Todo
            // better error handling
            // log error message
            //throw new RuntimeException("Failed to connect ", $response->status());
        }
    }

    private function synInProperties($records = [])
    {
        Log::info("Total properties for saving", [count($records)]);

        $finalParams = [];
        $propertyTypes = [];
        foreach ($records as $key => $value) {
            $finalParams[] = [
                'ex_property_id' => $value['uuid'],
                'county' => $value['county'],
                'country' => $value['country'],
                'town' => $value['town'],
                'description' => $value['description'],
                'address' => $value['address'],
                'image_full' => $value['image_full'],
                'image_thumbnail' => $value['image_thumbnail'],
                'latitude' => $value['latitude'],
                'longitude' => $value['longitude'],
                'num_bedrooms' => $value['num_bedrooms'],
                'num_bathrooms' => $value['num_bathrooms'],
                'price' => $value['price'],
                'property_type_id' => $value['property_type_id'],
                'type' => $value['type'],
                'source' => 'api',
                'property_created_at' => $value['created_at'],
                'property_updated_at' => $value['updated_at'],
            ];
            $propertyType = $value['property_type'];
            $propertyType['ex_property_type_id'] = $propertyType['id'];
            $propertyType['type_created_at'] = $propertyType['created_at'];
            $propertyType['type_updated_at'] = $propertyType['updated_at'];
            $propertyType['source'] = 'api';
            unset($propertyType['id']);
            unset($propertyType['created_at']);
            unset($propertyType['updated_at']);
            $propertyTypes[$propertyType['ex_property_type_id']] = $propertyType;
        }
        // update properties, if updated_at is not same.
        // if we want to use upsert, need to define the unique keys,
        // Property::upsert(
        //     $finalParams,
        //     ['ex_property_id', 'property_updated_at'],
        // );

        // PropertyType::upsert(
        //     $propertyTypes,
        //     ['ex_property_type_id', 'type_created_at'],
        // );

        // Todo: optimize in case of large data set
        // there are other ways to do the upsert

        collect($finalParams)->each(function (array $row) {
            Property::updateOrCreate(
                [
                    'ex_property_id' => $row['ex_property_id'],
                    'property_updated_at' => $row['property_updated_at']
                ],
                $row
            );
        });

        $propertyTypes = array_values($propertyTypes);
        collect($propertyTypes)->each(function (array $row) {
            PropertyType::updateOrCreate(
                [
                    'ex_property_type_id' => $row['ex_property_type_id'],
                    'type_updated_at' => $row['type_updated_at']
                ],
                $row
            );
        });

        Log::info("Total properties saved stats", ['properties' => count($finalParams), 'property_types' => count($propertyTypes)]);
    }
}
