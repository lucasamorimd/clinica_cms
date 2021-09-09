<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicoUpdateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => ['required', 'string', 'max:100'],
            'tipo_servico' => ['required', 'string', 'min:6'],
            'tempo_estimado' => ['required'],
            'preco' => ['required', 'numeric'],
            'descricao' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'nome.required' => 'Informar o nome do serviço',
            'tipo_servico.required' => 'Digitar o tipo do serviço (no plural)',
            'tipo_servico.min' => 'O tipo de serviço deve conter pelo menos 6 letras',
            'tempo_estimado.required' => 'Informar o tempo estimado de duração em horas',
            'preco.required' => 'Informar o valor do serviço',
            'descricao.required' => 'Informar uma descrição do serviço',

        ];
    }
}
