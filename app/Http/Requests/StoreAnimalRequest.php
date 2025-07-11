<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnimalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'caravana' => 'required|string|max:255',
            'caravana_oficial' => 'nullable|string|max:255',
            'rodeo_id' => 'required|exists:rodeos,id',
        ];
    }

    public function messages(): array
    {
        return [
            'caravana.required' => 'La caravana es obligatoria.',
            'caravana.string' => 'La caravana debe ser una cadena de texto.',
            'caravana.max' => 'La caravana no puede tener más de 255 caracteres.',
            'caravana_oficial.string' => 'La caravana oficial debe ser una cadena de texto.',
            'caravana_oficial.max' => 'La caravana oficial no puede tener más de 255 caracteres.',
            'rodeo_id.required' => 'El rodeo es obligatorio.',
            'rodeo_id.exists' => 'El rodeo seleccionado no existe.',
        ];
    }
}