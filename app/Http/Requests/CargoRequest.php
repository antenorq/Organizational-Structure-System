<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CargoRequest extends FormRequest
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
        if($this->atribuicoes != 1)//create e update
        {
            return [
                'id_tipo_cargo'=>'required',
                'descricao'=>'required|max:200',
                'qtde'=>'required|numeric',
                'grau'=>'required',
                'orgao.*'=>'required',
                'qtde_orgao.*'=>'required',
                //'atribuicao'=>'required',
                'ato_normativo'=>'required',
                'id_ato_normativo'=>'required',
                'id_tipo_acao_ato_normativo'=>'required',
            ];
        }
        else if($this->atribuicoes == 1)//update de atribuicoes
        {
            return [
                'orgao.*'=>'required',
                'qtde_orgao.*'=>'required',
                'atribuicao_generica'=>'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'id_tipo_cargo.required'=>'Tipo é obrigatório',
            'descricao.required'=>'Cargo é obrigatório',
            'descricao.max'=>'Cargo deve conter no máximo 200 caracteres',
            'qtde.required'=>'Quantidade é obrigatório',
            'qtde.numeric'=>'Quantidade deve conter apenas números',
            'grau.required'=>'Grau é obrigatório',
            'atribuicao.required'=>'Atribuição é obrigatório',
            'orgao.*.required'=>'O campo orgao deve conter pelo menos uma escolha',
            'qtde_orgao.*.required'=>'O campo quantidade deve conter pelo menos um registro',
            //'atribuicao.max'=>'Atribuição deve conter no máximo 4000 caracteres',
            //'grau.numeric'=>'Grau deve conter apenas números',
            'ato_normativo.required'=>'Ato Normativo é obrigatório',
            'id_ato_normativo.required'=>'Ato Normativo não preenchido corretamente',
            'id_tipo_acao_ato_normativo.required'=>'Ação é obrigatório',
        ];
    }
}
