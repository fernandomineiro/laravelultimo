<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnidadeRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => 'required',
            'telefone' => 'required',
            'endereco' => 'required|max:100',
            'cep' => 'required|numeric',
            'bairro' => 'required',
            'latitude' => 'max:10',
            'longitude' => 'max:10'
        ];
    }

    public function messages()
    {
        return [
            'numeric' => 'O campo :attribute é somente números.'
        ];
    }
    public function attributes()
    {
        return [
            
        ];
    }
}
