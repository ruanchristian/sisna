<?php

namespace App\Http\Requests;

use App\Services\Selection\RedistributionGraph;
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
            'redistribuicao' => 'required|json',
        ];
    }

    public function withValidator($validator): void {
        $validator->after(function ($validator) {
            $data = json_decode((string) $this->input('redistribuicao'), true);
            if (!is_array($data)) return; // a regra 'json' já reporta

            foreach (RedistributionGraph::fromConfig($data)->errors() as $error) {
                $validator->errors()->add('redistribuicao', $error);
            }
        });
    }

    public function messages(): array {
        return [
            'redistribuicao.required' => 'A regra de redistribuição de vagas não foi enviada.',
            'redistribuicao.json' => 'A regra de redistribuição de vagas está em um formato inválido.',
        ];
    }
}
