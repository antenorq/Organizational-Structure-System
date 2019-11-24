<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EstruturaOrganizacional;

use Charts;

class OrganogramaController extends Controller
{
	public $data;
	public $nivel;

	public function __construct()
	{
		//$this->middleware('auth');
		$this->estrutura = new EstruturaOrganizacional;		
		$this->data = array();		
	}

    public function index()
    {
    	$orgaos = $this->estrutura->getOrgaos();
    	return view('organograma.index', ['orgaos'=>$orgaos]);
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

	//public getOrgaosVinculados($orgao)
	//{

	//}

	//Busca as unidades do órgão
    public function getHierarquiaOrgao(Request $request)
	{
		
		//ADICIONA NO ARRAY OS ÓRGÃOS COLEGIADOS
		$orgaos_colegiados = $this->estrutura->getOrgaosColegiados($request->id_orgao);
		foreach($orgaos_colegiados as $orgao_colegiado)
		{
			$sigla_orgao = $this->estrutura->getSigla($orgao_colegiado->id);
			$descricao_orgao = $this->estrutura->getDescricao($orgao_colegiado->id);
			array_push($this->data, "{v:'$sigla_orgao', f:'<div class=\"orgaos_colegiados\"><div class=\"sigla\">$sigla_orgao</div>$descricao_orgao</div>'},'','$sigla_orgao'");
		}
		
		//ADICIONA NO ARRAY O ÓRGÃO ESCOLHIDO NO COMBO
		$sigla_orgao_pai = $this->estrutura->getSigla($request->id_orgao);
		$descricao_orgao_pai = $this->estrutura->getDescricao($request->id_orgao);
		array_push($this->data, "{v:'$sigla_orgao_pai', f:'<div class=\"orgao\"><div class=\"sigla\">$sigla_orgao_pai</div>$descricao_orgao_pai</div>'},'','$sigla_orgao_pai'");
		
		//ADICIONA NO ARRAY OS ÓRGÃOS VINCULADOS
		$orgaos_vinculados = $this->estrutura->getOrgaosVinculados($request->id_orgao);
		foreach($orgaos_vinculados as $orgao_vinculado)
		{
			$sigla_orgao = $this->estrutura->getSigla($orgao_vinculado->id);
			$descricao_orgao = $this->estrutura->getDescricao($orgao_vinculado->id);
			array_push($this->data, "{v:'$sigla_orgao', f:'<div class=\"vinculados\"><div class=\"sigla\">$sigla_orgao</div>$descricao_orgao</div>'},'','$sigla_orgao'");
		}
		
		//PEGA AS UNIDADES TOPO DO ORGAO PRIMEIRO
		$unidades = $this->estrutura->getHierarquiaOrgao($request->id_orgao);
		$unidades_topo = $unidades->where("id_unidade_subordinacao",null);
		//BUSCAS TODOS OS FILHOS DAS UNIDADES PAI (TOPO) DO ÓRGÃO ADICIONANDO NO ARRAY		
		foreach($unidades_topo as $unidade_topo)
		{
			$this->data =  $this->getFilhos($request->id_orgao,$unidade_topo->id);
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
	    		   		
        return view('organograma.organograma', ['chart' => $chart,'sigla_orgao_pai'=>$sigla_orgao_pai]);
	}
    
}



