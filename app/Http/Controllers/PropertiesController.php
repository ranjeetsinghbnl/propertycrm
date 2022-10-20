<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class PropertiesController extends Controller
{
    public function index()
    {
        return Inertia::render('Properties/Index', [
            'filters' => Request::all(Property::$allowedFilters),
            'properties' => Property::orderByTime()->with(['property_type'])
                ->filter(Request::only(Property::$allowedFilters))
                ->paginate(10)
                ->withQueryString()
                ->through(
                    fn ($property) => [
                        'id' => $property->id,
                        'ex_property_id' => $property->ex_property_id,
                        'county' => $property->county,
                        'country' => $property->country,
                        'town' => $property->town,
                        'description' => $property->description,
                        'address' => $property->address,
                        'image_full' => $property->image_full,
                        'image_thumbnail' => $property->image_thumbnail,
                        'latitude' => $property->latitude,
                        'longitude' => $property->longitude,
                        'num_bedrooms' => $property->num_bedrooms,
                        'num_bathrooms' => $property->num_bathrooms,
                        'price' => $property->price,
                        'property_type_id' => $property->property_type_id,
                        'type' => $property->type,
                        'property_created_at' => $property->property_created_at,
                        'property_updated_at' => $property->property_updated_at,
                        'source' => $property->source,
                        'type' => $property->type,
                        'zip' => $property->zip,
                        'source' => $property->source,
                        'type_details' => $property->property_type->title,
                    ]
                ),
            'propertyTypes' => PropertyType::orderByTitle()->select(['ex_property_type_id', 'title'])->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Properties/Create', [
            'propertyTypes' => PropertyType::orderByTitle()->select(['ex_property_type_id', 'title'])->get(),
            'saleType' => config('constants.saleType'),
        ]);
    }

    public function store(CreatePropertyRequest $request)
    {
        $validated = $request->validated();
        $latLon = Property::getLatLong();
        if ($validated['image_full']) {
            $image_full = Request::file('image_full')->store('properties');
        }
        Property::create([
            'county' => $validated['county'],
            'country' => $validated['country'],
            'town' => $validated['town'],
            'zip' => $validated['zip'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'num_bathrooms' => $validated['num_bathrooms'],
            'num_bedrooms' => $validated['num_bedrooms'],
            'price' => $validated['price'],
            'property_type_id' => $validated['property_type_id'],
            'type' => $validated['type'],
            'source' => 'manual',
            'longitude' => $latLon[0],
            'latitude' => $latLon[1],
            'image_full' => $image_full,
            'image_thumbnail' => $image_full,
        ]);

        return Redirect::route('properties.index')->with('success', 'Property created.');
    }

    public function edit(Property $property)
    {
        return Inertia::render('Properties/Edit', [
            'property' => [
                'id' => $property->id,
                'county' => $property->county,
                'country' => $property->country,
                'town' => $property->town,
                'zip' => $property->zip,
                'description' => $property->description,
                'address' => $property->address,
                'num_bathrooms' => $property->num_bathrooms,
                'num_bedrooms' => $property->num_bedrooms,
                'price' => $property->price,
                'property_type_id' => $property->property_type_id,
                'type' => $property->type,
                'source' => $property->source,
                'longitude' => $property->longitude,
                'latitude' => $property->latitude,
                'image_full' => $property->image_full,
                'image_thumbnail' => $property->image_thumbnail,
                'image_full_preview' => $property->image_full ? URL::route('image', ['path' => $property->image_full]) : null,
                'image_thumbnail_preview' => $property->image_full ? URL::route('image', ['path' => $property->image_full, 'w' => 200, 'h' => 200, 'fit' => 'crop']) : null,
            ],
            'propertyTypes' => PropertyType::orderByTitle()->select(['ex_property_type_id', 'title'])->get(),
            'saleType' => config('constants.saleType'),
        ]);
    }

    public function update(Property $property, UpdatePropertyRequest $request)
    {
        $validated = $request->validated();
        $property->update($validated);

        if (Request::file('image_full')) {
            $property->update(['image_full' => Request::file('image_full')->store('properties')]);
        }

        return Redirect::route('properties.index')->with('success', 'Property updated.');
    }

    public function destroy(Property $property)
    {
        $property->delete();
        // Todo translations
        return Redirect::back()->with('success', 'Property deleted.');
    }
}
