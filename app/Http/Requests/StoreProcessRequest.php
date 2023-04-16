<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcessRequest extends FormRequest
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
        $validYear = date('Y') + 1;

        return [
            'ano' => "required|integer|min:$validYear|unique:selective_processes"
        ];
    }

    public function messages(): array {
        return [
            'ano.unique' => "Já existe um processo seletivo criado para o ano informado."
        ];
    }
}
