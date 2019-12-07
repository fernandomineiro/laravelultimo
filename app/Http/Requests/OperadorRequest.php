<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class OperadorRequest extends FormRequest
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
            'senha' => 'required|min:8|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[@!$#%]).*$/',
            'senha_confirmation' => 'same:senha|required|min:8',
            'foto' => 'image',
            'dataNascimento' => 'date_format:d/m/Y',
            'perfil' => 'required',
            'nome' => 'required|max:255',
            'cpf' => 'required|cpf',
            'apelido' => 'nullable|max:20',
            'email' => 'required|email|unique:users,email',
            'operadora' => 'required',
            'unidade' => 'required_with:operadora',
            'telefone' => 'required',
            'celular' => 'nullable|',
            'ramal' => 'nullable|max:4'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'O campo :attribute precisa ser preenchido!',
            'senha.min' => 'O campo :attribute precisa ter pelo menos 8 caracteres.',
            'senha_confirmation.min' => 'O campo :attribute precisa ter pelo menos 8 caracteres.',
            'senha.regex' => 'O formato do valor informado no campo :attribute é inválido. É necessário ter letras, números e ao menos um caracter especial.',
            'confirmed' => 'As senhas não conferem!',
            'numeric' => 'O campo :attribute é somente números.',
        ];
    }
    public function attributes()
    {
        return [
            'senha_confirmation' => 'Confirmação de Senha',
        ];
    }

}
