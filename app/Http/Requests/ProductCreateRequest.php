<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'slug' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('products', 'slug')->ignore($this->route('product')),
            ],
            'quantity' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
            'image_name.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'image_title.*' => ['nullable', 'string', 'max:255'],
            'image_status.*' => ['nullable', 'boolean'],
            'attribute_id.*' => ['nullable', 'exists:attributes,id'],
            'values.*' => ['nullable', 'string', 'max:1000'],
            'attr_status.*' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please choose a category',
            'category_id.exists' => 'Category not found',
            'title.required' => 'Please enter title',
            'title.string' => 'Title must be a string',
            'title.min' => 'Title must be at least 3 characters',
            'title.max' => 'Title may not be greater than 255 characters',
            'slug.required' => 'Please enter slug',
            'slug.string' => 'Slug must be a string',
            'slug.min' => 'Slug must be at least 3 characters',
            'slug.max' => 'Slug may not be greater than 255 characters',
            'slug.unique' => 'Slug already exists',
            'quantity.required' => 'Please enter quantity',
            'quantity.integer' => 'Quantity must be an integer',
            'quantity.min' => 'Quantity must be at least 0',
            'price.required' => 'Please enter price',
            'price.numeric' => 'Price must be a valid number',
            'price.min' => 'Price must be at least 1',
            'discount.numeric' => 'Discount must be a valid number',
            'discount.min' => 'Discount must be at least 0',
        ];
    }
}
