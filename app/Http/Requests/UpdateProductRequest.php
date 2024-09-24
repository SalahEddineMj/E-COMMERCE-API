<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();
        if($method === 'PUT') {
            return [
                'name' => ['string', 'required', 'max:255'],
                'description' => ['string', 'required'],
                'image_url' => ['required', 'url'],
                'price' => ['required', 'numeric', 'min:0'],
                'category_id' => ['required', 'exists:categories,id'],
            ];
        }
        else {
            return [
                'name' => ['sometimes', 'string', 'required', 'max:255'],
                'description' => ['sometimes', 'string', 'required'],
                'image_url' => ['sometimes', 'required', 'url'],
                'price' => ['sometimes', 'required', 'numeric', 'min:0'],
                'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            ];
        }
    }

    protected function prepareForValidation() {
        $this->merge([
            'image_url' => $this->imageUrl,
            'category_id' => $this->categoryId
        ]);
    }
}
