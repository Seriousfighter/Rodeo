<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRodeoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'renspa' => 'nullable|string|max:255',
            'client_id' => 'required|exists:clients,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'location.string' => 'The location must be a string.',
            'location.max' => 'The location may not be greater than 255 characters.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'renspa.string' => 'The renspa must be a string.',
            'renspa.max' => 'The renspa may not be greater than 255 characters.',
            'client_id.required' => 'The client field is required.',
            'client_id.exists' => 'The selected client does not exist.',
        ];
    }
}
