<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
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
                ->through(fn ($property) => [
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
                    'created_at' => $property->created_at,
                    'updated_at' => $property->updated_at,
                    'type_details' => $property->property_type->title
                ]),
            'propertyTypes' => PropertyType::orderByTitle()->select(['ex_property_type_id', 'title'])->get()
        ]);
    }

    // public function create()
    // {
    //     return Inertia::render('Users/Create');
    // }

    // public function store()
    // {
    //     Request::validate([
    //         'first_name' => ['required', 'max:50'],
    //         'last_name' => ['required', 'max:50'],
    //         'email' => ['required', 'max:50', 'email', Rule::unique('users')],
    //         'password' => ['nullable'],
    //         'owner' => ['required', 'boolean'],
    //         'photo' => ['nullable', 'image'],
    //     ]);

    //     Auth::user()->account->users()->create([
    //         'first_name' => Request::get('first_name'),
    //         'last_name' => Request::get('last_name'),
    //         'email' => Request::get('email'),
    //         'password' => Request::get('password'),
    //         'owner' => Request::get('owner'),
    //         'photo_path' => Request::file('photo') ? Request::file('photo')->store('users') : null,
    //     ]);

    //     return Redirect::route('users')->with('success', 'User created.');
    // }

    public function edit(Property $property)
    {
        return Inertia::render('Properties/Edit', [
            'property' => [
                'id' => $property->id,
                'first_name' => $property->first_name,
                'last_name' => $property->last_name,
                'email' => $property->email,
                'owner' => $property->owner,
                'photo' => $property->photo_path ? URL::route('image', ['path' => $property->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'deleted_at' => $property->deleted_at,
            ],
        ]);
    }

    // public function update(User $user)
    // {
    //     if (App::environment('demo') && $user->isDemoUser()) {
    //         return Redirect::back()->with('error', 'Updating the demo user is not allowed.');
    //     }

    //     Request::validate([
    //         'first_name' => ['required', 'max:50'],
    //         'last_name' => ['required', 'max:50'],
    //         'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
    //         'password' => ['nullable'],
    //         'owner' => ['required', 'boolean'],
    //         'photo' => ['nullable', 'image'],
    //     ]);

    //     $user->update(Request::only('first_name', 'last_name', 'email', 'owner'));

    //     if (Request::file('photo')) {
    //         $user->update(['photo_path' => Request::file('photo')->store('users')]);
    //     }

    //     if (Request::get('password')) {
    //         $user->update(['password' => Request::get('password')]);
    //     }

    //     return Redirect::back()->with('success', 'User updated.');
    // }

    public function destroy(Property $property)
    {
        $property->delete();
        // Todo translations
        return Redirect::back()->with('success', 'Property deleted.');
    }
}
