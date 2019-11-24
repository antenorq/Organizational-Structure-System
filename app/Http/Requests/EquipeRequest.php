<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipeRequest extends FormRequest
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
            'gestor.id_orgao'=>'required|unique:gestor',
            'gestor.nome'=>'required|max:100',
            'gestor.id_cargo'=>'required',
            'gestor.foto'=>'required',
            'gestor.curriculo'=>'required|max:1000',
            'equipe.nomes.*'=>'required|max:100',
            'equipe.unidades.*'=>'required',
            'equipe.cargos.*'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'gestor.id_orgao.required'=>'Órgão do gestor é obrigatório',
            'gestor.id_orgao.unique'=>'Para este órgão já existe um gestor cadastrado',
            'gestor.nome.required'=>'Nome do gestor é obrigatório',
            'gestor.id_cargo.required'=>'Cargo do gestor é obrigatório',
            'gestor.foto.required'=>'Foto é obrigatório',
            'gestor.curriculo.required'=>'Currículo do gestor é obrigatório',
            'gestor.curriculo.max'=>'Currículo do gestor deve conter no máximo 1000 caracteres',
            'equipe.nomes.*.required'=>'Nome do membro da equipe deve ser preenchido',
            'equipe.unidades.*.required'=>'Unidade do membro da equipe deve ser atribuído',
            'equipe.cargos.*.required'=>'Cargo do membro da equipe deve ser atribuído',
        ];
    }
}
