<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = null;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ex_property_type_id',
        'title',
        'description',
        'type_created_at',
        'type_updated_at',
        'source'
    ];

    /**
     * Scope a query to only include property types for a given source.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $source
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfSource($query, $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Get the properties for the given property type.
     */
    public function properties()
    {
        return $this->hasMany(Property::class, 'property_type_id', 'ex_property_type_id');
    }
}
