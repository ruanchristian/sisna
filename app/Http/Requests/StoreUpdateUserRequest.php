<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateUserRequest extends FormRequest
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
    public function rules(): array {
        $id = $this->id ?? '';

        $rules = [
            'name' => 'required|string|max:40|min:3',
            'email' => "required|email|unique:users,email,$id,id",
            'password' => 'required|min:6|confirmed',
            'type' => [
                'required',
                Rule::in(['administrador', 'monitor'])
            ]
        ];

        if ($this->isMethod("PUT")) {
            $rules['password'] = 'nullable|min:6';
            $rules['type'] = 'nullable';
        }
        
        return $rules;
    }

    public function messages(): array {
        return [
            'type.required' => 'O campo cargo do usuário deve ser obrigatoriamente preenchido!',
            'type.in' => 'O campo cargo do usuário exige um valor entre [administrador ou monitor]',
            'password.confirmed' => 'As senhas não são iguais.'
        ];
    }
}
