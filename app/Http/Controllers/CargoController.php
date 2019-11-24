<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\CargoRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Cargo;
use App\Situacao;
use App\AtoNormativo;
use App\TipoAtoNormativo;
use App\TipoAcaoAtoNormativo;
use App\TipoCargo;
use App\EstruturaOrganizacional;
use App\CargoOrgao;
use DB;


class CargoController extends Controller
{
    public $cargo;
    public $atribuicao;
    public $ato;
    public $tipo_ato;
    public $tipo_acao_ato;
    public $tipo_cargo;

    public function __construct()
    {
        $this->middleware('auth');
        $this->helper = new Helpers;
        $this->cargo = new Cargo;
        $this->ato = new AtoNormativo;
        $this->tipo_ato = new TipoAtoNormativo;
        $this->tipo_acao_ato = new TipoAcaoAtoNormativo;
        $this->tipo_cargo = new TipoCargo;
    }

    public function index()
    {
        return view("cargo.index");
    }

    public function dadosGrid()
    {
        $cargos = Cargo::with('tipo', 'situacao')->get();

        return Datatables::of($cargos)
                ->addColumn('action', function ($cargo)
                {
                    return '
                        <a href="'.route('cargo.show', ['id'=>$cargo->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-search"></i> Exibir</a>
                        <a href="'.route('cargo.edit', ['id'=>$cargo->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Editar</a>
                        <a href="'.route('cargo.edit', ['id'=>$cargo->id,'atribuicoes'=>'1']).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Atribuições</a>
                    ';
                })
                ->make(true);
    }

    public function create()
    {
        $permissao = $this->helper->validarAcessoCreate();

        if ($permissao)
            return redirect('cargo')->with('status', $permissao['mensagem']);
              
        $tiposAcaoAtoNormativo = TipoAcaoAtoNormativo::whereIn('id', [1,2,5])->pluck('descricao', 'id');
        $tipos_ato_normativo = TipoAtoNormativo::pluck('descricao', 'id');
        $tiposCargo = TipoCargo::pluck('descricao', 'id');
        $orgaos = EstruturaOrganizacional::where('id_tipo_estrutura',1)->pluck('descricao', 'id');
        $select_default = [null => 'Selecione o órgão'];
        $orgaos = $select_default + $orgaos->toArray();
        $cargosOrgao = [];
        
        if (!empty(old('qtde_orgao'))) {
        	foreach(old('qtde_orgao') as $qtde_key => $qtde_value) {       		
        		$cargosOrgao[] = new \App\CargoOrgao(['id_orgao' =>  old('orgao.'. $qtde_key), 'qtde_orgao' => old('qtde_orgao.'. $qtde_key)]);
        	}
        }
        else 
        	$cargosOrgao[] = new \App\CargoOrgao;
       
        return view('cargo.create', ['tiposAcaoAtoNormativo'=>$tiposAcaoAtoNormativo,
                                    'tipos_ato_normativo'=>$tipos_ato_normativo,
                                    'tiposCargo'=>$tiposCargo,
                                    'orgaos'=>$orgaos,
                                    'cargosOrgao'=>$cargosOrgao ]);
    }

    public function store(CargoRequest $request)
    {
        $inputsCargo = $request->except(['ato_normativo']);
        $cargo = new Cargo;
        $cargo->fill($inputsCargo);
        $inputsorgao = $request->only('orgao', 'qtde_orgao');


        DB::beginTransaction();
        try {

            if(Auth::user()->id_perfil == 3)
                $cargo->id_situacao = 3;
            else
                $cargo->id_situacao = 1;

            $cargo->save();                

            //Salva as quantidades do cargo nos orgaos
            $qtd_total = 0;
            for($i = 0; $i < count($inputsorgao['orgao']); $i++)
            {
                $cargo_orgao = new CargoOrgao;
                $cargo_orgao->id_cargo   = $cargo->id;
                $cargo_orgao->id_orgao   = $inputsorgao['orgao'][$i];
                $cargo_orgao->qtde       = $inputsorgao['qtde_orgao'][$i];
                
                $qtd_total = $qtd_total + $cargo_orgao->qtde;
                $cargo_orgao->save();
            }

            if($qtd_total != $cargo->qtde)
            {
                DB::rollback();
                return back()->with('error', 'A quantidade distribuida nos órgãos está diferente do quantidade total informada.');
            }             

            DB::commit();
            return back()->with('success', 'Cargo cadastrado com sucesso!');
        }catch(\Exception $e){
            DB::rollback();
            return back()->with('error', 'Erro ao cadastrar Cargo, favor tente novamente!'.$e);
        }

    }

    public function show($id)
    {
        $cargo = $this->cargo->getCargoShow($id);
        $situacoes = Situacao::where('id', '=', 1)->pluck('descricao', 'id');

        return view('cargo.show', ['cargo'=>$cargo, 'situacoes'=>$situacoes]);
    }

    public function edit($id)
    {
        $objetoCargo = Cargo::find($id);
        $situacao = $objetoCargo->id_situacao;        
        $orgaos = EstruturaOrganizacional::where('id_tipo_estrutura',1)->pluck('descricao', 'id');
        $permissao = $this->helper->validarAcessoEdit($situacao);

        if ($permissao)
            return redirect('cargo')->with('status', $permissao['mensagem']);

        $cargo = Cargo::find($id);        
        $tiposAcaoAtoNormativo = TipoAcaoAtoNormativo::whereIn('id', [1,2,5,7])->pluck('descricao', 'id');
        $tipos_ato_normativo = $this->tipo_ato->pluck('descricao', 'id');
        $tiposCargo = $this->tipo_cargo->pluck('descricao', 'id');
        $select_default = [null => 'Selecione o órgão'];
        $orgaos = $select_default + $orgaos->toArray();
        $cargosOrgao = $objetoCargo->cargosOrgao;

        if(count($cargosOrgao) == 0)
        {
            $cargosOrgao[] = new \App\CargoOrgao;
        }
        //Seta o Ato (formatado para o objeto $cargosOrgao)
        else
        {
            foreach($cargosOrgao as $cargoOrgao)
            {
                $ato_normativo = $this->ato->getAto($cargoOrgao->id_ato_normativo)->first();
                if($ato_normativo)
                {
                    $cargoOrgao->setAttribute('ato',$ato_normativo->tipo.' - '.$ato_normativo->numero.' - '.$this->helper->date_system_format_oracle($ato_normativo->data));
                }           
            }
        }

        //Busca o ato normativo do cargo para exibir no campo número do ato
        $ato_normativo = $this->ato->getAto($cargo->id_ato_normativo);
        foreach($ato_normativo as $ato)
        {
            $ato = $ato->tipo .' - '. $ato->numero .' - '. $this->helper->date_system_format_oracle($ato->data);
        }

        return view('cargo.edit', ['cargo'=>$cargo,'tiposCargo'=>$tiposCargo,'tiposAcaoAtoNormativo'=>$tiposAcaoAtoNormativo, 'tipos_ato_normativo'=>$tipos_ato_normativo, 'ato'=>$ato,'orgaos'=>$orgaos,'cargosOrgao'=>$cargosOrgao ]);
    }

    public function update(CargoRequest $request, $id)
    {
        $inputsCargo = $request->except(['ato_normativo']);
        $cargo = $this->cargo->find($id);
        $cargo->fill($inputsCargo);

        $inputsorgao = $request->only('orgao', 'qtde_orgao','atribuicao_generica','id_ato_normativo');

        DB::beginTransaction();
        try {

            if(Auth::user()->id_perfil == 3)
                $cargo->id_situacao = 3;
            else
                $cargo->id_situacao = 1;

            //Se não tiver o request atribuições salva o cargo
            //Se tiver atribuicoes é por que veio da tela de atribuições e não salva cargo, somente atribuições
            if(!$request->atribuicoes)
            {
                $cargo->save();
            }

            //Salva as quantidades do cargo nos orgaos
            $qtd_total = 0;            
            for($i = 0; $i < count($inputsorgao['orgao']); $i++)
            {
                //AQUI É SE VEIO DA TELA DE CREATE OU EDIT SEM atribuicao_generica
                if(!$request->atribuicoes)
                {
                    if($inputsorgao['orgao'][$i] != null && $inputsorgao['qtde_orgao'][$i] != null)
                    {
                        $cargo_orgao = CargoOrgao::firstOrNew(['id_orgao' => $inputsorgao['orgao'][$i], 'id_cargo' => $cargo->id]);
                        $cargo_orgao->id_orgao   = $inputsorgao['orgao'][$i];
                        $cargo_orgao->qtde       = $inputsorgao['qtde_orgao'][$i];

                        $qtd_total = $qtd_total + $cargo_orgao->qtde;
                        $cargo_orgao->save();
                    }
                }                
                //AQUI É SE VEIO DA TELA DE EDIT com atribuicao_generica
                else
                {
                    if($inputsorgao['orgao'][$i] != null && $inputsorgao['qtde_orgao'][$i] != null)
                    {
                        $cargo_orgao = CargoOrgao::where('id_cargo',$cargo->id)->where('id_orgao',$inputsorgao['orgao'][$i])->first();
                
                        //$cargo_orgao = CargoOrgao::firstOrNew(['id_orgao' => $inputsorgao['orgao'][$i], 'id_cargo' => $cargo->id]);
                        $cargo_orgao->id_orgao   = $inputsorgao['orgao'][$i];
                        $cargo_orgao->qtde       = $inputsorgao['qtde_orgao'][$i];
                        $cargo_orgao->atribuicao_generica = $inputsorgao['atribuicao_generica'][$i];                       
                        $cargo_orgao->id_ato_normativo = $inputsorgao['id_ato_normativo'][$i];

                        $cargo_orgao->save();                                    
                    }
                }
                
                
            }

            //se não tiver o request atribuições verifica a quantidade de cargos
            if(!$request->atribuicoes)
            {
                if($qtd_total != $cargo->qtde)
                {
                    DB::rollback();
                    return back()->with('error', 'A quantidade distribuida nos órgãos está diferente do quantidade total informada.');
                }
            }
                         

            DB::commit();
            return back()->with('success', 'Cargo atualizado com sucesso!');
        }catch(\Exception $e){
            DB::rollback();
            return back()->with('error', 'Erro ao cadastrar Cargo, favor tente novamente!'.$e);
        }


    }

    public function buscaCargos(Request $request)
    {
        $cargos = $this->cargo->pluck('descricao', 'id');
        echo json_encode($cargos);
    }

    public function destroyOrgao($id)
    {
        $cargoOrgao = \App\CargoOrgao::find($id);
        if($cargoOrgao->delete()){
            return $cargoOrgao->toJson();
        }
    }

}