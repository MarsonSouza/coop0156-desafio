<?php

namespace App\Http\Requests;

use App\Enums\TipoCredito;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SolicitarAnaliseRequest extends FormRequest
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
            'cpf' => ['required', 'string', 'regex:/^\d{11}$/'],
            'renda_mensal' => ['required', 'numeric', 'gt:0'],
            'tipo_credito' => ['required', Rule::enum(TipoCredito::class)],
            'valor_solicitado' => ['required', 'numeric', 'gt:0'],
        ];
    }

    /** Normaliza o CPF (só dígitos) antes de validar contra o regex. */
    protected function prepareForValidation(): void
    {
        if ($this->has('cpf')) {
            $this->merge(['cpf' => preg_replace('/\D/', '', (string) $this->input('cpf'))]);
        }
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'cpf.regex' => 'O CPF deve conter exatamente 11 dígitos numéricos.',
            'renda_mensal.numeric' => 'A renda mensal deve ser um valor numérico.',
            'renda_mensal.gt' => 'A renda mensal deve ser maior que zero.',
            'valor_solicitado.numeric' => 'O valor solicitado deve ser um valor numérico.',
            'valor_solicitado.gt' => 'O valor solicitado deve ser maior que zero.',
            'tipo_credito.enum' => 'Selecione um tipo de crédito válido (pessoal, imobiliário ou automotivo).',
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
            'renda_mensal' => 'renda mensal',
            'tipo_credito' => 'tipo de crédito',
            'valor_solicitado' => 'valor solicitado',
        ];
    }
}
