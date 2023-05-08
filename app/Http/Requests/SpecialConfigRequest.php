<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecialConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this this.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the this.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        return [
            'vagas_pcd' => 'required|integer|min:0|max:45',
            'vagas_publica_ampla' => 'required|integer|min:0|max:45',
            'vagas_publica_prox' => 'required|integer|min:0|max:45',
            'vagas_private_ampla' => 'required|integer|min:0|max:45',
            'vagas_private_prox' => 'required|integer|min:0|max:45',
            'ordem_desempate' => 'required|json',
            
        ];
    }
}
