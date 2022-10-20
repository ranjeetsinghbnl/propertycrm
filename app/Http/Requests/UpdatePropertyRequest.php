<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'county' => ['required', 'max:50'],
            'country' => ['required', 'max:50'],
            'town' => ['required', 'max:50'],
            'zip' => ['required', 'max:20'],
            'description' => ['required'],
            'address' => ['required', 'max:100'],
            'num_bathrooms' => ['required', 'integer'],
            'num_bedrooms' => ['required', 'integer'],
            'price' => ['required', 'numeric'],
            'property_type_id' => ['required', 'exists:property_types,ex_property_type_id'],
            'type' => ['required']
        ];
    }
}
