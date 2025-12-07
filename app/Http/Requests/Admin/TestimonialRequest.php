<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'featured' => $this->featured == 'true',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'testimonial' => 'required|string|min:10|max:1000',
            'status' => 'required|in:pending,approved,rejected',
            'featured' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'testimonial.required' => 'O depoimento é obrigatório.',
            'testimonial.min' => 'O depoimento deve ter no mínimo 10 caracteres.',
            'testimonial.max' => 'O depoimento deve ter no máximo 1000 caracteres.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser: pendente, aprovado ou rejeitado.',
        ];
    }
}
