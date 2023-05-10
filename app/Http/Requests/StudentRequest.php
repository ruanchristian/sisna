<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
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
        return [
            'nome' => 'required|string|max:50',
            'data_nascimento' => 'required|date',
            'media_pt' => 'required|numeric|min:0|max:10',
            'media_mt' => 'required|numeric|min:0|max:10',
            'media_final' => 'required|numeric|min:0|max:10',
            'origem' => [
                'required',
                Rule::in(['PUBLICA-AMPLA', 'PUBLICA-PROX-EEEP', 'PRIVATE-AMPLA', 'PRIVATE-PROX-EEEP', 'PCD'])
            ],
            'curso_id' => 'required|integer',
        ];
    }

    public function messages(): array {
        return [
            'origem.in' => 'A origem do participante deve ser entre [Pública Ampla Concorrência, Pública Residente Próximo, Particular Ampla Concorrência, Particular Residente Próximo, PCD].',
            'data_nascimento.required' => 'A data de nascimento tem que ser preenchida.',
            'media_pt.required' => 'A média de português tem que ser setada.',
            'media_mt.required' => 'A média de matemática tem que ser setada.',
            'media_final.required' => 'A média final tem que ser setada.',
            'media_pt.max' => 'A média de português tem que ser menor ou igual a 10.',
            'media_mt.max' => 'A média de matemática tem que ser menor ou igual a 10.',
            'media_final.max' => 'A média final tem que ser menor ou igual a 10.'
        ];
    }
}
