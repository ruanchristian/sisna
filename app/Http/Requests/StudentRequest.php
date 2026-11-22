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

    protected function prepareForValidation(): void {
        $this->merge([
            'doc_comprovante_idade' => $this->boolean('doc_comprovante_idade'),
            'doc_comprovante_residencia' => $this->boolean('doc_comprovante_residencia'),
            'doc_historico' => $this->boolean('doc_historico'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     * Os campos da ficha de inscrição são obrigatórios em novas inscrições,
     * mas opcionais na edição (alunos antigos não possuem esses dados).
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        $ficha = $this->isMethod('post') ? 'required' : 'nullable';

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

            'endereco' => "$ficha|string|max:120",
            'numero' => 'nullable|string|max:10',
            'cep' => ['nullable', 'regex:/^\d{5}-?\d{3}$/'],
            'bairro' => "$ficha|string|max:80",
            'telefone_1' => "$ficha|string|max:20",
            'telefone_2' => 'nullable|string|max:20',
            'escola_origem_tipo' => [$ficha, Rule::in(['ESTADUAL', 'MUNICIPAL', 'PARTICULAR'])],
            'escola_origem_nome' => "$ficha|string|max:120",
            'responsavel_nome' => "$ficha|string|max:100",
            'responsavel_parentesco' => "$ficha|string|max:30",
            'doc_comprovante_idade' => 'boolean',
            'doc_comprovante_residencia' => 'boolean',
            'doc_historico' => 'boolean',
        ];
    }

    public function withValidator($validator): void {
        $validator->after(function ($validator) {
            $tipo = $this->input('escola_origem_tipo');
            $origem = (string) $this->input('origem');

            if (!$tipo || $origem === 'PCD') return;

            $particular = $tipo === 'PARTICULAR';
            if ($particular !== str_starts_with($origem, 'PRIVATE')) {
                $validator->errors()->add('origem',
                    'A categoria não confere com a escola de origem: escola ' .
                    ($particular ? 'particular' : 'pública (estadual/municipal)') .
                    ' exige categoria ' . ($particular ? 'Particular' : 'Pública') . '.');
            }
        });
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
            'media_final.max' => 'A média final tem que ser menor ou igual a 10.',
            'endereco.required' => 'O endereço tem que ser preenchido.',
            'bairro.required' => 'O bairro tem que ser preenchido.',
            'cep.regex' => 'O CEP deve estar no formato 00000-000.',
            'telefone_1.required' => 'Informe ao menos um telefone.',
            'escola_origem_tipo.required' => 'Informe se a escola de origem é estadual, municipal ou particular.',
            'escola_origem_nome.required' => 'Informe o nome da escola de origem.',
            'responsavel_nome.required' => 'Informe o nome do responsável pelo aluno.',
            'responsavel_parentesco.required' => 'Informe o parentesco do responsável.',
        ];
    }
}
