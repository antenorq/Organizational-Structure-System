<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AtoNormativoRequest extends FormRequest
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
        //Caso create    
        if($this->method() == "POST")
        {
            return [
                'numero'=>'required|numeric|unique:ato_normativo',
                'id_tipo_ato_normativo'=>'required',
                'data'=>'required',
                'caput'=>'required|max:400',
                'obs_ato_normativo'=>'max:400',
                'fl_tem_doc'=>'required',
                //'documento'=>'required|mimes:pdf,doc,docx,odt',
                'documento' => 'required_if:fl_tem_doc,1|mimes:pdf,doc,docx,odt',
                'introducao' => 'required_if:fl_tem_doc,0',
                'conteudo' => 'required_if:fl_tem_doc,0',
            ];
        }
        else
        {
            return [
                'numero'=>'required|numeric|unique:ato_normativo,numero,'. $this->atonormativo,
                'id_tipo_ato_normativo'=>'required',
                'data'=>'required',
                'caput'=>'required|max:400',
                'obs_ato_normativo'=>'max:400',
                'fl_tem_doc'=>'required',
                //'documento'=>'required|mimes:pdf,doc,docx,odt',
                'documento' => 'required_if:fl_tem_doc,1|mimes:pdf,doc,docx,odt',
                'introducao' => 'required_if:fl_tem_doc,0',
                'conteudo' => 'required_if:fl_tem_doc,0',
            ];
        }
    }

    public function messages()
    {
        return [
            'numero.required'=>'Campo número é obrigatório',
            'numero.max'=>'Campo número deve conter no máximo 30 digitos',
            'numero.numeric'=>'Campo número deve conter apenas números',
            'numero.unique'=>'Número já cadastrado, por favor insira outro',
            'id_tipo_ato_normativo.required'=>'Campo tipo é obrigatório',
            'data.required'=>'Campo data é obrigatório',
            'caput.required'=>'Campo caput é obrigatório',
            'caput.max'=>'Campo caput deve conter no máximo 400 caracteres',
            'obs_ato_normativo.max'=>'Campo observação deve conter no máximo 400 caracteres',            
            'documento.mimes'=>'Campo documento deve possuir apenas arquivo nos formatos pdf, doc, docx ou odt',
            'fl_tem_doc.required'=>'Obrigatório responder a pergunta sobre o documento',
            'documento.required_if' => 'Campo documento é obrigatório se foi marcado sim',
            'introducao.required_if' => 'Campo introducao é obrigatório se foi marcado não',
            'conteudo.required_if' => 'Campo conteudo é obrigatório se foi marcado não',
        ];
    }
}
