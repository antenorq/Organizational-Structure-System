<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\AtoNormativoRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\EstruturaOrganizacional;
use App\Cargo;
use App\AtoNormativo;
use App\Situacao;
use DB;

class AtoNormativoController extends Controller
{
	public $helper;
	public $ato;

	public function __construct()
	{
		$this->middleware('auth');
		$this->ato = new AtoNormativo;
		$this->helper = new Helpers;
	}

	public function index()
	{
		return view('ato_normativo.index');
	}

	public function dadosGrid()
	{
		$atosNormativos = AtoNormativo::with('tipo', 'situacao')->get();

		return Datatables::of($atosNormativos)
				->addColumn('action', function ($atonormativo)
				{
					$retorno = '
                		<!-- <a href="'.route('atonormativo.show', ['id'=>$atonormativo->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-search"></i> Exibir</a>-->
                		<a href="'.route('atonormativo.edit', ['id'=>$atonormativo->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Editar</a>                		
                	';
                	if($atonormativo->fl_tem_doc)
                	{
                		$retorno .= '<a href="'.asset($atonormativo->caminho_documento).'" target="_blank" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-save-file"></i> Pdf</a>';                		
                	}
                	else
                	{
                		$retorno .= '<a href="'.route('atonormativo.visualizacao', ['id'=>$atonormativo->id]).'" target="_blank" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-list-alt"></i> Doc</a>';
                	}

                	
                	return $retorno;

				}) 
				->editColumn('data', function($atonormativo) {
					return $this->helper->date_system_format_oracle($atonormativo->data);
				})
				->editColumn('data_publicacao', function($atonormativo) {
					return $this->helper->date_system_format_oracle($atonormativo->data_publicacao);
				})
				->make(true);
	}

	public function create()
	{
        $permissao = $this->helper->validarAcessoCreate();

        if ($permissao)
            return redirect('atonormativo')->with('status', $permissao['mensagem']);

		$tipos_ato_normativo = \App\TipoAtoNormativo::pluck('descricao', 'id');

		return view('ato_normativo.create', ['tipos_ato_normativo'=>$tipos_ato_normativo]);
	}

	public function store(AtoNormativoRequest $request)
	{
		$inputs = $request->except(['documento']);

		$atoNormativo = new AtoNormativo;
		$atoNormativo->fill($inputs);
		$atoNormativo->observacao = $inputs['obs_ato_normativo'];
		$atoNormativo->data = $this->helper->date_db_format($atoNormativo->data);
		$atoNormativo->data_publicacao = $this->helper->date_db_format($atoNormativo->data_publicacao);

		//Efetua upload do arquivo
		if($atoNormativo->fl_tem_doc)
		{
			$caminho = $this->upload($request->file('documento'));
			$atoNormativo->caminho_documento = $caminho;
		}

		if(Auth::user()->id_perfil == 3)
			$atoNormativo->id_situacao = 3;
		else
			$atoNormativo->id_situacao = 1;


		if($atoNormativo->save())
            return back()->with('success', 'Ato normativo salvo com sucesso!');
        else
            return back()->with('error', 'Houve um erro ao cadastro produto, por favor tente novamente!');
	}

	public function show($id)
	{
		$ato = $this->ato->getAtoShow($id);

		$situacoes = Situacao::where('id', '=', 1)->pluck('descricao', 'id');
	
		$ato->data = $this->helper->date_system_format_oracle($ato->data);
		$ato->data_publicacao = $this->helper->date_system_format_oracle($ato->data_publicacao);

		return view('ato_normativo.show', ['ato'=>$ato, 'situacoes'=>$situacoes]);
	}

	public function visualizacao($id)
	{
		$ato = $this->ato->getAtoShow($id);
	
		$ato->data = $this->helper->date_system_format_oracle($ato->data);
		$ato->data_publicacao = $this->helper->date_system_format_oracle($ato->data_publicacao);

		return view('ato_normativo.visualizacao', ['ato'=>$ato]);
	}

	public function edit($id)
	{
		//VERIFICA SE O USUÁRIO POSSUI PERMISSÃO PARA ACESSAR A EDIÇÃO DO ATO NORMATIVO
        $atoNormativo = AtoNormativo::find($id);
        $permissao = $this->helper->validarAcessoEdit($atoNormativo->id_situacao);

        if($permissao)
            return redirect('atonormativo')->with('status', $permissao['mensagem']);

        //VERIFICAR SE O ATO NORMATIVO ESTÁ VINCULADO A UMA ESTRUTURA ORGANIZACIONAL OU CARGO
        $vinculado = $this->getVinculacao($id);

        if($vinculado)
        	return redirect('atonormativo')->with('status', 'Este ato normativo não pode ser editado porque já está atrelado a uma estrutura organizacional ou cargo.');

		$atoNormativo->data = $this->helper->date_system_format_oracle($atoNormativo->data);
		$atoNormativo->data_publicacao = $this->helper->date_system_format_oracle($atoNormativo->data_publicacao);
		$atoNormativo['obs_ato_normativo'] = $atoNormativo->observacao;

		$tipos_ato_normativo = \App\TipoAtoNormativo::pluck('descricao', 'id');

		return view('ato_normativo.edit', ['atoNormativo'=>$atoNormativo, 'tipos_ato_normativo'=>$tipos_ato_normativo]);
	}

	public function update(AtoNormativoRequest $request, $id)
	{
		$atoNormativo = AtoNormativo::find($id);
		$inputs = $request->except(['documento']);

		$atoNormativo->fill($inputs);
		$atoNormativo->observacao = $inputs['obs_ato_normativo'];
		$atoNormativo->data = $this->helper->date_db_format($atoNormativo->data);
		$atoNormativo->data_publicacao = $this->helper->date_db_format($atoNormativo->data_publicacao);

		if(Auth::user()->id_perfil == 3)
			$atoNormativo->id_situacao = 3;
		else
			$atoNormativo->id_situacao = 1;

		//EFETUA O UPLOAD DO DOCUMENTO
		if($atoNormativo->fl_tem_doc)
		{
			if($request->documento)
			{
				$caminho = $this->upload($request->file('documento'));
				$atoNormativo->caminho_documento = $caminho;
			}
		}


		if($atoNormativo->save())
            return back()->with('success', 'Ato normativo salvo com sucesso!');
        else
            return back()->with('error', 'Houve um erro ao cadastro produto, por favor tente novamente!');
	}

    public function upload($file)
    {
    	$nameFile = date('Y-m-d-H-i-s_') . $file->getClientOriginalName();
		$newNameFile = $this->helper->retiraCaractereEspecial($nameFile);

        $file->move(public_path() . "/documentos/", $newNameFile);
     	$caminho = "/documentos/". $newNameFile;

     	return $caminho;
    }

    //Busca os atos normativos e retorna para o campo autocomplete
    public function buscaAtos()
    {
    	$input = strtolower($_GET['term']);
    	$atos = $this->ato->getAtos($input);

    	$data = array();
    	foreach($atos as $key => $value)
    		$data[] = array('id' => $value->id, 'value' =>  $value->tipo .' - '. $value->numero .' - '. $this->helper->date_system_format_oracle($value->data));

    	return json_encode($data);
    }

    public function getVinculacao($id) 
    {
		$estrutura = new EstruturaOrganizacional;
		$vinculo_estrutura = $estrutura->where('id_ato_normativo', $id)->get();

		$cargo = new Cargo;
		$vinculo_cargo = $estrutura->where('id_ato_normativo', $id)->get();

		if(!$vinculo_estrutura->isEmpty() || !$vinculo_cargo->isEmpty())
			return true;
		else
			return false;
    }

}
