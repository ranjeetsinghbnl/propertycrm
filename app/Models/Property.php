<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'price' => 'double',
        'latitude' => 'double',
        'longitude' => 'double',
        'num_bedrooms' => 'integer',
        'num_bathrooms' =>  'integer'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ex_property_id',
        'county',
        'country',
        'town',
        'description',
        'address',
        'image_full',
        'image_thumbnail',
        'latitude',
        'longitude',
        'num_bedrooms',
        'num_bathrooms',
        'price',
        'property_type_id',
        'type',
        'property_created_at',
        'property_updated_at',
        'source',
        'zip'
    ];

    /**
     * Allowed filtered columns.
     *
     * @var array
     */
    public static $allowedFilters = [
        'search',
        'min_price',
        'max_price',
        'bed',
        'bath',
        'property_type_id',
        'type',
        'source',
        'zip'
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    /**
     * Scope a query to sort by time
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByTime($query)
    {
        $query->orderBy('created_at', 'desc')->orderBy('updated_at', 'desc');
    }

    /**
     * Scope a query to only include property based on given filter.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->orWhere('county', 'like', '%' . $search . '%')
                ->orWhere('country', 'like', '%' . $search . '%')
                ->orWhere('town', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%');
        })->when($filters['min_price'] ?? null, function ($query, $min_price) {
            $query->where('price', '>=', $min_price);
        })->when($filters['max_price'] ?? null, function ($query, $min_price) {
            $query->where('price', '<=', $min_price);
        })->when($filters['bed'] ?? null, function ($query, $bed) {
            $query->where('num_bedrooms', $bed);
        })->when($filters['bath'] ?? null, function ($query, $bath) {
            $query->where('num_bathrooms', $bath);
        })->when($filters['property_type_id'] ?? null, function ($query, $property_type_id) {
            $query->where('property_type_id', $property_type_id);
        })->when($filters['type'] ?? null, function ($query, $type) {
            $query->where('type', $type);
        })->when($filters['source'] ?? null, function ($query, $source) {
            $query->where('source', $source);
        })->when($filters['zip'] ?? null, function ($query, $zip) {
            $query->where('zip', $zip);
        });
    }

    /**
     * Scope a query to only include property types for a given source.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $source
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSource($query, $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Scope a query to only include properties with a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the property type.
     */
    public function property_type()
    {
        return $this->hasOne(PropertyType::class, 'ex_property_type_id', 'property_type_id');
    }

    /***
     * Get a lat long randomly
     * Not a good place, might be google or other service be useful.
     */
    public static function getLatLong()
    {
        $lang = 33.7490;
        $long = -84.3880;

        $latitude = fake()->latitude(
            ($lang * 10000 - rand(0, 50)) / 10000,
            ($lang * 10000 + rand(0, 50)) / 10000
        );

        $longitude = fake()->longitude(
            $min = ($long * 10000 - rand(0, 50)) / 10000,
            $max = ($long * 10000 + rand(0, 50)) / 10000
        );
        return [$longitude, $latitude];
    }
}
