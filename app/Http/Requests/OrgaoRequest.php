<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Helpers;
use Illuminate\Http\Request;

class OrgaoRequest extends FormRequest
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
        if($this->method() == "POST")
        {
            return [
                'descricao' => 'required|unique:estrutura_organizacional',
                //'sigla' => 'required|unique:estrutura_organizacional',
                'sigla' => 'required',
                'id_sit_estr_organizacional' => 'required',
                'data_fim'=> 'required_if:id_sit_estr_organizacional,4|required_if:id_sit_estr_organizacional,5',
                'id_funcao' => 'required',
                'id_tipo_hierarquia' =>'required',
                'competencia' => 'required',
                'finalidade' => 'required',
                'cnpj' => 'required|cnpj|unique:estrutura_organizacional',
                'email'=>'required|email',
                'telefone'=>'required',
                'site'=>'required|url',
                'hora_inicio' =>'required',
                'hora_fim'=>'required',

                //form ato normativo - escolha
                'id_ato_normativo'=> 'required',
                'ato_normativo'=>'required',
                'id_tipo_acao_ato_normativo'=> 'required',

                //endereco
                'endereco.cep'=>'required',
                'endereco.logradouro'=>'required',
                'endereco.bairro'=>'required',
                'endereco.numero'=>'required|numeric',
            ];
        }
        else 
        {
            $idEstruturaOrganizacional = ( is_null($this->orgao) ? "" : ",".$this->orgao );

            return [
                'descricao' => 'required|unique:estrutura_organizacional,descricao,' . $request->orgao,
                //'sigla' => 'required|unique:estrutura_organizacional,sigla,' . $request->orgao,
                'sigla' => 'required',
                'id_sit_estr_organizacional'=> 'required',
                'data_fim.required_with' => 'Data fim é obrigatório',
                'id_funcao'=> 'required',
                'id_tipo_hierarquia'=>'required',
                'competencia'=> 'required',
                'finalidade'=> 'required',
                'cnpj' => 'required|cnpj|unique:estrutura_organizacional,cnpj'.$idEstruturaOrganizacional,
                'email'=>'required|email',
                'telefone'=>'required',
                'site'=>'required|url',
                'hora_inicio'=>'required',
                'hora_fim'=>'required',
                
                //form ato normativo - escolha
                'id_ato_normativo'=> 'required',
                'ato_normativo'=>'required',
                'id_tipo_acao_ato_normativo'=> 'required',

                //endereco
                'endereco.cep'=>'required',
                'endereco.logradouro'=>'required',
                'endereco.bairro'=>'required',
                'endereco.numero'=>'required|numeric',
            ];
        }

        /*'id_unidade_representacao'=>'required_if:id_tipo_hierarquia,1',
        'id_orgao_vinculacao'=>'required_unless:id_tipo_hierarquia,1',*/
    }

    public function messages()
    {
        return [
            'descricao.required'=>'Descrição é obrigatório',
            'descricao.unique'=>'Descrição já existe em outro órgão e não pode repetir',
            'sigla.required'=>'Sigla é obrigatório',
            //'sigla.unique'=>'Sigla já existe em outro órgão e não pode repetir',
            'id_sit_estr_organizacional.required'=>'Situação é obrigatório',
            'data_fim.required_if' => 'Data fim é obrigatório',
            'id_funcao.required'=>'Função é obrigatório',
            'id_tipo_hierarquia.required'=>'Tipo de Administração é obrigatório',
            'competencia.required'=>'Competência é obrigatório',
            'finalidade.required'=>'Finalidade é obrigatório',
            'cnpj.required'=>'Cnpj é obrigatório',
            'email.required'=>'Email é obrigatório',
            'email.email'=>'Formato de email errado. Favor corrigir',
            'site.required'=>'Site é obrigatório',
            'site.url'=>'Formato do site inválido',
            'telefone.required'=>'Telefone é obrigatório',
            'hora_inicio.required'=>'Horário de ínicio é obrigatório',
            'hora_fim.required'=>'Horário final é obrigatório',
            'id_unidade_representacao.required_if'=>'Unidade de Representação é obrigatório',
            'id_unidade_representacao.unique'=>'Unidade de Representação não pode se repetir',
            'id_orgao_vinculacao.required_unless'=>'Relação Hierarquica é obrigatório',

            //form ato normativo - escolha
            'ato_normativo.required'=>'Ato Normativo é obrigatório',
            'id_ato_normativo.required'=>'Ato Normativo não preenchido corretamente',
            'id_tipo_acao_ato_normativo.required'=> 'Ação do Ato Normativo é Obrigatória',

            //endereco
            'endereco.cep.required'=>'Cep é obrigatório',
            'endereco.logradouro.required'=>'Logradouro é obrigatório',
            'endereco.bairro.required'=>'Bairro é obrigatório',
            'endereco.numero.required'=>'Número é obrigatório',
            'endereco.numero.numeric'=>'Número deve conter apenas números',
        ];
    }
}
