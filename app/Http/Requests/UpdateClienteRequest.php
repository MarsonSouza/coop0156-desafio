<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Atualização parcial: com `sometimes`, cada campo só é validado quando enviado.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $clienteId = $this->route('cliente')?->id;

        return [
            'nome' => ['sometimes', 'required', 'string', 'max:255'],
            'cpf' => [
                'sometimes', 'required', 'string', 'regex:/^\d{11}$/',
                Rule::unique('clientes', 'cpf')->ignore($clienteId),
            ],
            'email' => [
                'sometimes', 'required', 'email', 'max:255',
                Rule::unique('clientes', 'email')->ignore($clienteId),
            ],
            'telefone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'renda_mensal' => ['sometimes', 'required', 'numeric', 'gt:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'cpf.regex' => 'O CPF deve conter exatamente 11 dígitos numéricos.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'renda_mensal.numeric' => 'A renda mensal deve ser um valor numérico.',
            'renda_mensal.gt' => 'A renda mensal deve ser maior que zero.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nome' => 'nome',
            'cpf' => 'CPF',
            'email' => 'e-mail',
            'telefone' => 'telefone',
            'renda_mensal' => 'renda mensal',
        ];
    }
}
