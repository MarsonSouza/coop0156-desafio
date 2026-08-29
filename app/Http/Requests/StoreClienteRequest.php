<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'regex:/^\d{11}$/', 'unique:clientes,cpf'],
            'email' => ['required', 'email', 'max:255', 'unique:clientes,email'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'renda_mensal' => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'nome.max' => 'O nome pode ter no máximo 255 caracteres.',
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
