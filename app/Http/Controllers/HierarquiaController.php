<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EstruturaOrganizacional;

class HierarquiaController extends Controller
{
	public $data;
	public $nivel;

	public function __construct()
	{
		$this->middleware('auth');
		$this->estrutura = new EstruturaOrganizacional;		
		$this->data = array();
		$this->nivel = 0;
	}

	
    public function index()
    {
    	$orgaos = $this->estrutura->getOrgaos();
    	return view('hierarquia.index', ['orgaos'=>$orgaos]);
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
			$this->nivel = 0;
			$sigla = "<div class='unidade_pai'>".$unidade->sigla." - ".$unidade->descricao."</div>";
		}
		else
		{			
			$sigla = $this->escreveIdentacao($this->getNivelSubordinacao($unidade->id));
			$sigla = $sigla.$unidade->sigla." - ".$unidade->descricao;
		}

		array_push($this->data, $sigla);

		if($filhos)
		{
			foreach($filhos as $filho)
			{				
				$this->getFilhos($orgao,$filho->id);	
			}
		}

		return $this->data;
	}

	public function getNivelSubordinacao($unidade)
	{	
		$unidade = $this->estrutura->find($unidade);

		$nivel=0;
		while($unidade->id_unidade_subordinacao != NULL)
		{
			$nivel++;
			$unidade = $this->estrutura->find($unidade->id_unidade_subordinacao);
			//$this->getNivelSubordinacao($unidade->id_unidade_subordinacao);
		}

		return $nivel;
	}

	public function escreveIdentacao($nivel)
	{		
		$identacao = "";
		for($i=0; $i < $nivel;$i++)
		{
			$identacao = " &nbsp;&nbsp;&nbsp; ".$identacao;
		}
		return $identacao." - ";	
	}


	//Busca as unidades do órgão
	public function getHierarquiaOrgao(Request $request)
	{
		//PEGA AS UNIDADES TOPO PRIMEIRO
		$unidades = $this->estrutura->getHierarquiaOrgao($request->orgao);
		$unidades_topo = $unidades->where("id_unidade_subordinacao",null);
		
		foreach($unidades_topo as $unidade_topo)
		{
			$hierarquia_orgao = $this->getFilhos($request->orgao,$unidade_topo->id);
		}

		return json_encode($hierarquia_orgao);
	}
    
}
