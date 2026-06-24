<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryCreateRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'rank' => ['required', 'integer', 'min:1', 'max:100'],
            'slug' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($this->route('category')),
            ],
            'status' => ['required', 'boolean'],
        ];
    }
}
