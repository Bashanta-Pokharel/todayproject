<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'title'  => 'required|string|min:3|max:255',
            'slug'   => 'required|string|min:3|max:255|unique:categories',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|integer|min:1',
            'discount' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please choose a category',
            'category_id.exists' => 'Category not found',
            'title.required'  => 'Please enter title',
            'title.string'    => 'Title must be a string',
            'title.min'       => 'Title must be at least 3 characters',
            'title.max'       => 'Title may not be greater than 255 characters',
            'slug.required'  => 'Please enter slug',
            'slug.string'    => 'Slug must be a string',
            'slug.min'       => 'Slug must be at least 3 characters',
            'slug.max'       => 'Slug may not be greater than 255 characters',
            'slug.unique'    => 'Slug already exists',
            'quantity.required'  => 'Please enter quantity',
            'quantity.integer'  => 'Quantity must be an integer',
            'quantity.min'      => 'Quantity must be at least 1',
            'quantity.max'      => 'Quantity may not be greater than 255',
            'price.required'  => 'Please enter price',
            'price.integer'  => 'Price must be an integer',
            'price.min'      => 'Price must be at least 1',
            'price.max'      => 'Price may not be greater than 255',
            'discount.required'  => 'Please enter discount',
            'discount.integer'  => 'Discount must be an integer',
            'discount.min'      => 'Discount must be at least 1',
            'discount.max'      => 'Discount may not be greater than 255'
        ];
    }
}
