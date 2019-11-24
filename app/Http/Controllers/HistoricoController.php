<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EstruturaOrganizacional;
use App\AtoNormativo;
use App\TipoAcaoAtoNormativo;
use App\Historico;
use App\Http\Helpers;

class HistoricoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->estrutura = new EstruturaOrganizacional;
        $this->historico = new Historico;
        $this->tipo_acao_ato_normativo = new TipoAcaoAtoNormativo;
        $this->helper = new Helpers;
    }

    public function index() 
    {
        return view('historico.index');
    }

    public function historico(Request $request)
    {        
        $historico = $this->historico->getHistorico($request->estrutura);
        
        //dd($historico);

        foreach($historico as $key => $value)
        {            
            $data[] = $value;
            
            //DATA       
            if($historico[$key]->data == null)
            {
                $data[$key]['data'] = '-';
            }
            else
            {
                $data[$key]['data'] = $this->helper->date_system_format_oracle($historico[$key]->data);
            }

            //SIGLA
            if($historico[$key]->sigla == null){$data[$key]['sigla'] = '-';}

            //DESCRIÇÃO
            if($historico[$key]->descricao == null){$data[$key]['descricao'] = '-';}

            //TIPO AÇÃO DO ATO
            if($historico[$key]->id_tipo_acao_ato_normativo == null)
            {
                $data[$key]['tipo_acao_ato_normativo']['descricao'] = '-';
            }

            //ATO
            if($historico[$key]->id_ato_normativo == null)
            {
                $historico[$key]->id_ato_normativo = '-';
            }
            else
            {
                $data[$key]['ato'] = $this->getAtoNormativo($historico[$key]->id_ato_normativo);
            }

            
            
        }

        //dd($data);


        return json_encode($data);
    }

    public function historicoAto(Request $request)
    {
        $historico = $this->historico->getHistoricoAto($request->estrutura);

        foreach($historico as $key => $value)
        {
            $data[] = $value;
            $data[$key]['data'] = $this->helper->date_system_format_oracle($historico[$key]->data);
            $data[$key]['ato'] = $this->getAtoNormativo($historico[$key]->id_ato_normativo);
            $data[$key]['tipo_acao_ato_normativo'] = $this->tipo_acao_ato_normativo->getAcao($historico[$key]->id_tipo_acao_ato_normativo);
            $data[$key]['data_publicacao'] = $this->helper->date_system_format_oracle($historico[$key]->atoNormativo->data_publicacao);
        }

        return json_encode($data);
    }

    //RETORNA O ATO NORMATIVO NO FORMATO DE EXIBIÇÃO, EXEMPLO: LEI - 2222 - 2017
    public function getAtoNormativo($id_ato)
    {
        $helper = new Helpers;
        $atoNormativo = new AtoNormativo;
        $ato = $atoNormativo->getAto($id_ato);
        
        return $ato[0]->tipo ." - ". $ato[0]->numero ." - ". $helper->date_system_format_oracle($ato[0]->data);
    }

    public function orgao()
    {
        $orgaos = $this->estrutura->getOrgaos();
        return view('historico.orgao', ['orgaos'=>$orgaos]);
    }

    public function unidade()
    {
        $orgaos = $this->estrutura->getOrgaos();
        $unidades = $this->estrutura->getUnidades();
        return view('historico.unidade', ['unidades'=>$unidades, 'orgaos'=>$orgaos]);
    }

    public function orgaoColegiado()
    {
        $orgaos_colegiados = $this->estrutura->getConselhos();
        return view('historico.orgao-colegiado', ['orgaos_colegiados'=>$orgaos_colegiados]);
    }

    public function atoNormativo()
    {
        $orgaos = $this->estrutura->getOrgaos();
        return view('historico.ato-normativo', ['orgaos'=>$orgaos]);
    }

   
}
