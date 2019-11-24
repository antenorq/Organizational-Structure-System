<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\CargoUnidade;

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

    public function rules(Request $request)
    {        
        $unidadeRules  = [
            'descricao' => 'required',
            'sigla' => 'required',
            'id_sit_estr_organizacional'=> 'required',
            'data_fim'=> 'required_if:id_sit_estr_organizacional,4|required_if:id_sit_estr_organizacional,5',
            'id_tipo_hierarquia'=>'required',
            'cargo.*'=>'required',
            'qtde.*'=>'required',
            //'competencia'=> 'max:4000',
            //'finalidade'=> 'max:4000',
            //'competencia'=> 'required',
            //'finalidade'=> 'required',
            'email'=>'required|email',
            'telefone'=>'required',
            'id_orgao_unidade'=>'required',

            //form ato normativo - escolha
            'id_ato_normativo'=> 'required',
            'ato_normativo'=>'required',
            'id_tipo_acao_ato_normativo'=> 'required',
        ];

        $request = $this->request->all();   
        if(isset($request['cargo']) AND isset($request['qtde']))         
        {
            $validaCargo = $this->validaQtdCargos($request);                           
            if(!$validaCargo)
            {
               $rules = array_merge($unidadeRules, ['qtdes_cargo'=>'required']);  
               return $rules; 
            }
        }

        return $unidadeRules;
    }

    public function messages()
    {
        return [
            'descricao.required'=>'Descrição é obrigatório',
            'sigla.required'=>'Sigla é obrigatório',
            //'sigla.unique'=>'Sigla já existe em outro órgão e não pode repetir',
            'id_sit_estr_organizacional.required'=>'Situação é obrigatório',
            'data_fim.required_if' => 'Data fim é obrigatório',
            'id_tipo_hierarquia.required'=>'Tipo de Unidade é obrigatório',
            //'competencia.max'=>'Competência pode ter até 4000 caracteres',
            //'finalidade.max'=>'Finalidade pode ter até 4000 caracteres',
            //'competencia.required'=>'Competência é obrigatório',
            //'finalidade.required'=>'Finalidade é obrigatório',
            'email.required'=>'Email é obrigatório',
            'email.email'=>'Formato de email errado. Favor corrigir',
            'telefone.required'=>'Telefone é obrigatório',
            'id_orgao_unidade.required'=>'Órgão é obrigatório',
            'cargo.*.required'=>'O campo cargo deve conter pelo menos uma escolha',
            'qtde.*.required'=>'O campo quantidade deve conter pelo menos um registro',
            'qtdes_cargo.required'=>'A quantidade de cargos disponíveis é menor do que a quantidade requerida',

            //form ato normativo - escolha
            'ato_normativo.required'=>'Ato Normativo é obrigatório',
            'id_ato_normativo.required'=>'Ato Normativo não preenchido corretamente',
            'id_tipo_acao_ato_normativo.required'=> 'Ação do Ato Normativo é Obrigatória',

        ];
    }
    
    public function validaQtdCargos($request)
    {
        if(!in_array(null, $request['cargo']) AND !in_array(null, $request['qtde']))
        {
            for($i = 0; $i < count($request['cargo']); $i++)
            {   
                $cargo_unidade = new CargoUnidade;
                $cargo_unidade->id_cargo   = $request['cargo'][$i];
                $cargo_unidade->qtde       = $request['qtde'][$i];
                
                // Verifica se é possível alocar a quantidade de cargos pedida pelo usuário em cada um dos cargos;
                $qtdeCargosDisponiveis = $cargo_unidade->getQtdeCargosDisponiveis($cargo_unidade->id_cargo);

                if($this->method() == "POST")
                    if($cargo_unidade->qtde > $qtdeCargosDisponiveis)
                        return false;
            }
        } 
        else { 
            return false; 
        }  

        return true;
    }
}