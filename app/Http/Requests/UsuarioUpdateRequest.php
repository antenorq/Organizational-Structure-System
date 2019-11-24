<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioUpdateRequest extends FormRequest
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
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users,email,' . $this->id,
            'password' => 'required|max:255',
            'password_confirm' => 'required|max:255|same:password',
        ];
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
        ];
    }
}
