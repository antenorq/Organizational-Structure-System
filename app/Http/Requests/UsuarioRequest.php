<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UsuarioRequest extends FormRequest
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
    public function rules(Request $request)
    {
        if($request->method == "POST")
        {
            return [
                'name' => 'required|max:255',
                'id_orgao' => 'required',
                'id_perfil' => 'required',
                'email' => 'required|max:255|unique:users',
                'password' => 'required|max:255',
                'password_confirm' => 'required|max:255|same:password',
                
            ];    
        }
        else
        {
            return [
                'name' => 'required|max:255',
                'email' => 'required|max:255|unique:users,email,' . $request->usuario,
                'id_orgao' => 'required',
                'id_perfil' => 'required',
            ];
        }
        
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome é obrigatório',
            'name.max' => 'Nome deve conter no máximo 255 caracteres',
            'email.required' => 'E-mail é obrigatório',
            'email.max' => 'E-mail deve conter no máximo 255 caracteres',
            'email.unique' => 'E-mail já cadastrado, insira outro',
            'password.required' => 'Senha é obrigatório',
            'password.max' => 'Senha deve conter no máximo 255 caracteres',
            'password_confirm.required' => 'Confirmar senha é diferente da senha digitado',
            'password_confirm.same' => 'Confirmar senha é diferente da senha digitado',
            'password_confirm.max' => 'Confirmar senha deve conter no máximo 255 caracteres',
            'id_orgao.required' => 'Órgão é obrigatório',
            'id_perfil.required' => 'Perfil é obrigatório',
        ];
    }
}
