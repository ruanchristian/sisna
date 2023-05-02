<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
        $colors = ['#007bff', '#ff0000', '#ffc107', '#9400d3', '#008000', '#964b00'];

        return [
            'nome' => 'required|string|max:20|min:3|unique:courses',
            'cor_curso' => ['required', Rule::in($colors), 'unique:courses']
        ];
    }

    public function messages(): array {
        return [
            'cor_curso.required' => 'É necessário definir uma cor para o curso.',
            'cor_curso.in' => 'O campo "cor do curso" requer um valor entre [azul, vermelho, roxo, verde, marrom ou amarelo].',
            'nome.max' => 'O campo "nome do curso" não pode conter mais de 20 caracteres.',
            'nome.unique' => 'Já existe esse curso!',
            'cor_curso.unique' => 'Já existe um curso com essa cor. Escolha outra diferente!'
        ];
    }
}