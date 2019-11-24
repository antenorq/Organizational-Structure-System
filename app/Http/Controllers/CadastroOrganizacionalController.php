<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EstruturaOrganizacional;
use App\AtoNormativo;
use App\TipoAtoNormativo;
use App\CargoUnidade;
use App\Http\Helpers;
//use App\Http\OrganogramaController
//use PDF;
use Charts;

class CadastroOrganizacionalController /* extends PDFController OrganogramaController */
{
    public $estrutura;
    public $atoNormativo;
    public $tipoAtoNormativo;
    public $cargoUnidade;

    public function __construct()
    {
        $this->estrutura = new EstruturaOrganizacional();
        $this->atoNormativo = new AtoNormativo();
        $this->tipoAtoNormativo = new TipoAtoNormativo();
        $this->cargoUnidade = new CargoUnidade();
        $this->helper = new Helpers;
        $this->data = array();
    }

    public function index()
    {   
        $orgaos = $this->estrutura->getOrgaos();
    	return view('cadastro-organizacional.index', ['orgaos'=>$orgaos]);
    }

    public function getCadastroOrganizacional(Request $request)
    {
        //BUSCA OS DADOS DO ÓRGÃO
        $orgao = EstruturaOrganizacional::where('id', $request->id_orgao)->with('tipoHierarquia', 'atoNormativo')->get()->first();        
        $orgao->atoNormativo->data = $this->helper->date_system_format_relatorio($orgao->atoNormativo->data);
        $orgao->atoNormativo->tipo_ato_normativo = $this->tipoAtoNormativo->getTipo($orgao->atoNormativo->id_tipo_ato_normativo);

        //BUSCA OS CARGOS COMISSIONADOS E AS FUNÇÕES DE CONFIANÇA DO ÓRGÃO
        $cargosUnidade = $this->cargoUnidade->getCargosUnidades($request->id_orgao, 1);
        $funcoesUnidade = $this->cargoUnidade->getCargosUnidades($request->id_orgao, 2);

        //BUSCA TODOS OS ATOS NORMATIVOS LIGADOS AO ÓRGÃO
        $atosNormativos = $this->atoNormativo->getAtosOrgao($request->id_orgao);

        //$chart - ORGANOGRAMA
        $chart = $this->getHierarquiaOrgao($request->id_orgao);

        return view('cadastro-organizacional.pdf', ['chart' => $chart,'sigla_orgao_pai'=>$orgao->sigla, 'orgao'=>$orgao, 'cargosUnidade'=>$cargosUnidade, 'funcoesUnidade'=>$funcoesUnidade, 'atosNormativos'=>$atosNormativos]);
        //$config = ['view' => 'cadastro-organizacional.pdf', 'name' => 'documento.pdf' , 'data' => ['orgao'=>$orgao, 'cargosUnidade'=>$cargosUnidade, 'funcoesUnidade'=>$funcoesUnidade, 'atosNormativos'=>$atosNormativos]]; 
               
        //return parent::geraPDF($config);
    }


    public function getHierarquiaOrgao($id_orgao)
    {
        
        //ADICIONA NO ARRAY OS ÓRGÃOS COLEGIADOS
        $orgaos_colegiados = $this->estrutura->getOrgaosColegiados($id_orgao);
        foreach($orgaos_colegiados as $orgao_colegiado)
        {
            $sigla_orgao = $this->estrutura->getSigla($orgao_colegiado->id);
            $descricao_orgao = $this->estrutura->getDescricao($orgao_colegiado->id);
            array_push($this->data, "{v:'$sigla_orgao', f:'<div class=\"orgaos_colegiados\"><div class=\"sigla\">$sigla_orgao</div>$descricao_orgao</div>'},'','$sigla_orgao'");
        }
        
        //ADICIONA NO ARRAY O ÓRGÃO ESCOLHIDO NO COMBO
        $sigla_orgao_pai = $this->estrutura->getSigla($id_orgao);
        $descricao_orgao_pai = $this->estrutura->getDescricao($id_orgao);
        array_push($this->data, "{v:'$sigla_orgao_pai', f:'<div class=\"orgao\"><div class=\"sigla\">$sigla_orgao_pai</div>$descricao_orgao_pai</div>'},'','$sigla_orgao_pai'");
        
        //ADICIONA NO ARRAY OS ÓRGÃOS VINCULADOS
        $orgaos_vinculados = $this->estrutura->getOrgaosVinculados($id_orgao);
        foreach($orgaos_vinculados as $orgao_vinculado)
        {
            $sigla_orgao = $this->estrutura->getSigla($orgao_vinculado->id);
            $descricao_orgao = $this->estrutura->getDescricao($orgao_vinculado->id);
            array_push($this->data, "{v:'$sigla_orgao', f:'<div class=\"vinculados\"><div class=\"sigla\">$sigla_orgao</div>$descricao_orgao</div>'},'','$sigla_orgao'");
        }
        
        //PEGA AS UNIDADES TOPO DO ORGAO PRIMEIRO
        $unidades = $this->estrutura->getHierarquiaOrgao($id_orgao);
        $unidades_topo = $unidades->where("id_unidade_subordinacao",null);
        //BUSCAS TODOS OS FILHOS DAS UNIDADES PAI (TOPO) DO ÓRGÃO ADICIONANDO NO ARRAY      
        foreach($unidades_topo as $unidade_topo)
        {
            $this->data =  $this->getFilhos($id_orgao,$unidade_topo->id);
        }
        
        //MONTA O GRÁFICO ORGANOGRAMA GOOGLE CHART ORGCHART
        $chart = Charts::create('orgchart', 'google')
                ->labels
                    (
                        [
                            "{label: 'Name', type: 'string'}", 
                            "{label: 'Manager', type: 'string'}", 
                            "{label: 'ToolTip', type: 'string'} "
                        ]
                    )
                ->values($this->data);
                        
        return $chart;
    }


    //FUNÇÃO RECURSIVA PARA LISTAR A HIERARQUIA DO ORGÃO
    public function getFilhos($orgao,$unidade_pai)
    {   
        $unidades = $this->estrutura->getHierarquiaOrgao($orgao);
        $filhos = $unidades->where("id_unidade_subordinacao",$unidade_pai);

        //PEGA A UNIDADE EM QUESTÃO DO LOOP RECURSIVO
        $unidade = $unidades->where("id",$unidade_pai)->first();

        //PEGAS AS UNIDADES PAI
        if($unidade->id_unidade_subordinacao == NULL)
        {
            $sigla_pai = $this->estrutura->getSigla($orgao);
            $descricao_pai = $this->estrutura->getDescricao($orgao);

            if($unidade->id_tipo_hierarquia == 61)          
                $class = "assessoria";          
            else            
                $class = "unidades_pai";
            
            array_push($this->data, "{v:'$unidade->sigla', f:'<div class=\"$class\"><div class=\"sigla\">$unidade->sigla</div>$unidade->descricao</div>'},'$sigla_pai','$unidade->sigla'");
        }
        else
        {
            $sigla_pai = $this->estrutura->getSigla($unidade->id_unidade_subordinacao);
            $descricao_pai = $this->estrutura->getDescricao($unidade->id_unidade_subordinacao);         
            array_push($this->data, "{v:'$unidade->sigla', f:'<div class=\"subordinacao\"><div class=\"sigla\">$unidade->sigla</div>$unidade->descricao</div>'},'$sigla_pai','$unidade->sigla'");       
        }

        if($filhos)
        {
            foreach($filhos as $filho)
            {               
                $this->getFilhos($orgao,$filho->id);    
            }
        }       

        return $this->data;
    }


}
