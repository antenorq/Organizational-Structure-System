<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OrgaoColegiadoRequest extends FormRequest
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
        return [
            'descricao' => 'required|unique:estrutura_organizacional,descricao,' . $request->orgaocolegiado,
            'sigla' => 'required|unique:estrutura_organizacional,sigla,' . $request->orgaocolegiado,
            'id_sit_estr_organizacional'=> 'required',
            'data_fim'=> 'required_if:id_sit_estr_organizacional,4|required_if:id_sit_estr_organizacional,5',
            'id_tipo_hierarquia'=>'required',
            'id_funcao'=> 'required',
            'competencia'=> 'required',
            'finalidade'=> 'required',

            //form ato normativo - escolha
            'id_ato_normativo'=> 'required',
            'ato_normativo'=>'required',
            'id_tipo_acao_ato_normativo'=> 'required',
        ];
    }

    public function messages()
    {
        return [
            'descricao.required'=>'Descrição é obrigatório',
            'sigla.required'=>'Sigla é obrigatório',
            'id_sit_estr_organizacional.required'=>'Situação é obrigatório',
            'data_fim.required_if' => 'Data fim é obrigatório',
            'id_tipo_hierarquia.required'=>'Tipo Órgão Colegiado é obrigatório',
            'id_funcao.required'=>'Função é obrigatório',
            'competencia.required'=>'Competência é obrigatório',
            'finalidade.required'=>'Finalidade é obrigatório',

            //form ato normativo - escolha
            'ato_normativo.required'=>'Ato Normativo é obrigatório',
            'id_ato_normativo.required'=>'Ato Normativo não preenchido corretamente',
            'id_tipo_acao_ato_normativo.required'=> 'Ação do Ato Normativo é Obrigatória',

        ];
    }
}
