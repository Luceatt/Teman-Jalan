<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class StorePlaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: Add authorization logic when auth is integrated
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'category_id' => ['required', 'exists:place_categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => __('place name'),
            'description' => __('place description'),
            'address' => __('address'),
            'latitude' => __('latitude'),
            'longitude' => __('longitude'),
            'category_id' => __('category'),
            'image' => __('place image'),
            'is_active' => __('active status'),
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('Place name is required'),
            'address.required' => __('Address is required'),
            'latitude.required' => __('Latitude is required'),
            'longitude.required' => __('Longitude is required'),
            'category_id.required' => __('Please select a category'),
            'category_id.exists' => __('Selected category is invalid'),
            'image.image' => __('File must be an image'),
            'image.mimes' => __('Image must be jpeg, png, jpg, gif, or webp format'),
            'image.max' => __('Image size must not exceed 2MB'),
        ];
    }
}