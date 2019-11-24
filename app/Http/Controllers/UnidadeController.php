<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UnidadeRequest;
use Illuminate\Support\Facades\Auth;

use App\EstruturaOrganizacional;
use App\Funcao;
use App\Situacao;
use App\AtoNormativo;
use App\TipoAcaoAtoNormativo;
use App\TipoHierarquia;
use App\Endereco;
use App\Cargo;
use App\CargoUnidade;
use App\Services\HistoricoService;
use App\Http\Helpers;
use DB;
use Yajra\Datatables\Datatables;

class UnidadeController extends Controller
{
    public $estrutura_organizacional;
    public $ato;
    public $helper;

    public function __construct()
    {
        $this->estrutura_organizacional = new EstruturaOrganizacional;
        $this->ato = new AtoNormativo;
        $this->cargo_unidade = new CargoUnidade;
        $this->helper = new Helpers;
        $this->middleware('auth');
    }
 
    public function index()
    {
        return view('unidade.index');
    }


    public function dadosGridUnidade(Request $request)
    {
        //BUSCA TODAS AS UNIDADES E FILTRA AS QUE ESTÃO ATIVAS, PENDENTES E TEMPORÁRIOS
        $unidades = EstruturaOrganizacional::where('id_tipo_estrutura',2)->whereIn('id_sit_estr_organizacional', [1,3,5])->with('orgaoUnidade','situacao')->get();
               
        //SE O TIPO FOR UNIDADE ORGAOS NÃO EXTINTOS, BUSCA TODAS AS UNIDADES, EXCETO AS QUE ESTÃO COM O ÓRGÃO EXTINTO
        if($request->tipo == "unidade-orgaos-nao-extintos")
        {
            //Verifica se o órgão da unidade está extinta, caso sim, não adiciona no array
            foreach($unidades as $key => $value)
            {
                $orgao = EstruturaOrganizacional::where('id', $value->id_orgao_unidade)->get()->first();            
                
                if($orgao->id_sit_estr_organizacional != 4)
                    $data[] = $value;
            }

            $unidades = $data;    
        }
        
        return Datatables::of($unidades)
            ->addColumn('action', function ($unidade)
            {
                return '
                    <a href="'.route('unidade.show', ['id'=>$unidade->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-search"></i> Exibir</a>
                    <a href="'.route('unidade.edit', ['id'=>$unidade->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Editar</a>
                ';
            })
            ->make(true);
    }

    public function create()
    {
        $unidade = new EstruturaOrganizacional;
        $tiposAcaoAtoNormativo = TipoAcaoAtoNormativo::whereIn('id', [1,2,5])->pluck('descricao', 'id');
        $tiposHierarquia = TipoHierarquia::where('id_tipo_estrutura',2)->orderBy('descricao')->pluck('descricao', 'id');
        $orgaos = EstruturaOrganizacional::where('id_tipo_estrutura',1)->pluck('descricao', 'id');
        $cargos = Cargo::orderBy('descricao')->pluck('descricao', 'id');
        $cargosUnidade = [];
        
        if (!empty(old('qtde'))) {
        	foreach(old('qtde') as $qtde_key => $qtde_value) {
        		$cargosUnidade[] = new \App\CargoUnidade(['id_cargo' =>  old('cargo.'. $qtde_key), 'qtde' => old('qtde.'. $qtde_key)]);
        	}
        }
        else 
        	$cargosUnidade[] = new \App\CargoUnidade;
        
        $permissao = $this->helper->validarAcessoCreate();

        if ($permissao)
            return redirect('unidade')->with('status', $permissao['mensagem']);

        $cargosDesabilitados = [];

        //Adiciona a quantidade de cargos disponiveis junto ao nome do cargo
        foreach ($cargos as $id => $descricao) {
            $qtdeCargosDisponiveis = $this->cargo_unidade->getQtdeCargosDisponiveis($id);
            $qtdeCargosDisponiveis = ($qtdeCargosDisponiveis < 0) ? 0 : $qtdeCargosDisponiveis;
            $nomeDoCampo = $descricao .' - '. "Disponível: ". $qtdeCargosDisponiveis;
            $cargos[$id] = $nomeDoCampo;

            if ($qtdeCargosDisponiveis == 0)
                $cargosDesabilitados[] = $nomeDoCampo;
        }

        $select_default = [null => 'Selecione o cargo'];
        $cargos = $select_default + $cargos->toArray();

        //Caso o usuário tenha o perfil adm ou aprovador, o combo conterá as opções ativo, pendente e temporário
        if(Auth::user()->id_perfil == 1 || Auth::user()->id_perfil == 2)
            $situacoes = Situacao::whereIn('id', [1, 3, 5])->orderBy('descricao')->pluck('descricao', 'id');
        else
            $situacoes = Situacao::where('id', 3)->orderBy('descricao')->pluck('descricao', 'id');

        return view('unidade.create',['situacoes'=>$situacoes,
                                    'tiposAcaoAtoNormativo'=>$tiposAcaoAtoNormativo,
                                    'tiposHierarquia'=>$tiposHierarquia,
                                    'orgaos'=>$orgaos,
                                    'cargos'=>$cargos,                                    
                                    'cargosUnidade' => $cargosUnidade,
                                    'cargosDesabilitados'=>$cargosDesabilitados]);
    }

    public function store(UnidadeRequest $request)
    {
        $historico = new HistoricoService();
        $unidade = new EstruturaOrganizacional();

        //Campo do tipo CLOB por causa de miséra do oracle tem que inserir no final do INSERT
        //Por isso foi dado o Except nos campos CLOB ('competencia','finalidade') para inserir por último.
        $inputs_unidade = $request->except('competencia','finalidade');
        
        $inputsCargo = $request->only('cargo', 'qtde');

        //Preenche no Model os valores que veio do POST Formulário
        $unidade->fill($inputs_unidade);

        //Força esse valor pois sei que esse create é do tipo Unidade
        $unidade->id_tipo_estrutura = 2; // Estrutura Unidade

        //Formata o campo data fim para o oracle
        $unidade->data_fim = $this->helper->date_db_format($unidade->data_fim);
        
        $unidade->competencia = $request->competencia;
        $unidade->finalidade = $request->finalidade;

        //dd($unidade);
        DB::beginTransaction();
        try {
            //Salva unidade
            $unidade->save();
            //Salva histórico se o perfil do usuário for adm
            if(Auth::user()->id_perfil == 1)
                $historico->salvarDados($unidade);

            //Salva os cargos da unidade
            for($i = 0; $i < count($inputsCargo['cargo']); $i++)
            {
                $cargo_unidade = new CargoUnidade;
                $cargo_unidade->id_cargo   = $inputsCargo['cargo'][$i];
                $cargo_unidade->qtde       = $inputsCargo['qtde'][$i];
                $cargo_unidade->id_unidade = $unidade->id;

                /*
                *   Verifica se é possível alocar a quantidade de cargos
                *       pedida pelo usuário em cada um dos cargos;
                */
                $qtdeCargosDisponiveis = $this->cargo_unidade->getQtdeCargosDisponiveis($cargo_unidade->id_cargo);

                if($cargo_unidade->qtde > $qtdeCargosDisponiveis)
                    return back()->with('error', 'A quantidade de cargos disponíveis é menor do que a quantidade requerida.');

                $cargo_unidade->save();
            }

            DB::commit();
            return back()->with('success', 'Unidade cadastrada com sucesso!');
        }catch(\Exception $e){
            DB::rollback();
            return back()->with('error', 'Erro ao cadastrar unidade, favor tente novamente!'.$e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $unidade = $this->estrutura_organizacional->getShowUnidade($id);
        $unidade->data = $this->helper->date_system_format_oracle($unidade->data);
        $unidade->data_fim = $this->helper->date_system_format_oracle($unidade->data_fim);

        $unidade_subordinada = null;

        //Busca a unidade subordinada
        if($unidade->id_unidade_subordinacao)
            $unidade_subordinada = $this->estrutura_organizacional->getHierarquia($unidade->id_unidade_subordinacao);

        //Busca os cargos da unidade
        $cargos = $this->cargo_unidade->getCargoUnidade($id);

        //Busca as situações diferente de pendente
        $situacoes = Situacao::where('id', '!=', 3)->pluck('descricao', 'id');

        return view('unidade.show', ['unidade'=>$unidade, 'cargos'=>$cargos, 'unidade_subordinada'=>$unidade_subordinada, 'situacoes'=>$situacoes]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $objetoUnidade = EstruturaOrganizacional::find($id);
        $situacao = $objetoUnidade->id_sit_estr_organizacional;

        $permissao = $this->helper->validarAcessoEdit($situacao);

        if ($permissao)
            return redirect('unidade')->with('status', $permissao['mensagem']);

        $select_default = [null => 'Selecione'];
        $unidade = EstruturaOrganizacional::find($id);
        $situacoes = Situacao::orderBy('descricao')->pluck('descricao', 'id');
        $tiposAcaoAtoNormativo = TipoAcaoAtoNormativo::whereIn('id', [1,2,5,7])->pluck('descricao', 'id');
        $tiposHierarquia = TipoHierarquia::where('id_tipo_estrutura',2)->pluck('descricao', 'id');
        $orgaos = $this->estrutura_organizacional->getOrgaos();        
        $unidades = $select_default + $this->estrutura_organizacional->getUnidades($unidade->id_orgao_unidade)->forget($id)->toArray();
        $cargos = Cargo::orderBy('descricao')->pluck('descricao', 'id');
        $cargosUnidade = $unidade->cargosUnidade;

        //dd($cargosUnidade);

        $cargosDesabilitados = [];

        if(count($cargosUnidade) == 0)
        {
            $cargosUnidade[] = new \App\CargoUnidade;
        }        

        //Adiciona a quantidade de cargos disponiveis junto ao nome do cargo
        foreach ($cargos as $id => $descricao) {
            $qtdeCargosDisponiveis = $this->cargo_unidade->getQtdeCargosDisponiveis($id);
            $qtdeCargosDisponiveis = ($qtdeCargosDisponiveis < 0) ? 0 : $qtdeCargosDisponiveis;
            $nomeDoCampo = "$descricao - Disponível: $qtdeCargosDisponiveis";
            $cargos[$id] = $nomeDoCampo;
            if ($qtdeCargosDisponiveis == 0)
                $cargosDesabilitados[] = $nomeDoCampo;
        }

        $unidade->data_fim = $this->helper->date_system_format_oracle($unidade->data_fim);

        //Caso o usuário tenha o perfil adm ou aprovador, o combo conterá todas as opções
        if(Auth::user()->id_perfil == 1 || Auth::user()->id_perfil == 2)
            $situacoes = Situacao::orderBy('descricao')->pluck('descricao', 'id');
        else
            $situacoes = Situacao::where('id', 3)->orderBy('descricao')->pluck('descricao', 'id');

        //Busca o ato normativo da unidade para exibir no campo número do ato
        $ato_normativo = $this->ato->getAto($unidade->id_ato_normativo);
        foreach($ato_normativo as $ato)
        {
            $ato = $ato->tipo .' - '. $ato->numero .' - '. $this->helper->date_system_format_oracle($ato->data);
        }
        
        return view('unidade.edit',['unidade'=>$unidade,
                                    'situacoes'=>$situacoes,
                                    'tiposAcaoAtoNormativo'=>$tiposAcaoAtoNormativo,
                                    'tiposHierarquia'=>$tiposHierarquia,
                                    'orgaos'=>$orgaos,
                                    'unidades'=>$unidades,
                                    'cargos'=>$cargos,
                                    'ato'=>$ato,
                                    'cargosUnidade' => $cargosUnidade,
                                    'cargosDesabilitados'=>$cargosDesabilitados]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UnidadeRequest $request, $id)
    {
        $unidade = EstruturaOrganizacional::find($id);
        $historico = new HistoricoService();

        $inputs_unidade = $request->all();

        $inputsCargo = $request->only('cargo', 'qtde');

        //Preenche no Model os valores que veio do POST Formulário
        $unidade->fill($inputs_unidade);

        //Força esse valor pois sei que esse create é do tipo Unidade
        $unidade->id_tipo_estrutura = 2; // Estrutura Unidade

        //Formata o campo data fim para o oracle
        $unidade->data_fim = $this->helper->date_db_format($unidade->data_fim);

        DB::beginTransaction();
        try {
            //Salva unidade
            $unidade->save();
            //Salva histórico se o perfil do usuário for adm
            if(Auth::user()->id_perfil == 1)
                $historico->salvarDados($unidade);

            //Salva os cargos da unidade
            for($i = 0; $i < count($inputsCargo['cargo']); $i++)
            {
                if($inputsCargo['cargo'][$i] != null && $inputsCargo['qtde'][$i] != null){
                    $cargoUnidade = CargoUnidade::firstOrNew(['id_cargo' => $inputsCargo['cargo'][$i], 'id_unidade' => $unidade->id]);
                    $qtdeCargosDisponiveis = $this->cargo_unidade->getQtdeCargosDisponiveis($cargoUnidade->id_cargo);
                    $qtdeCargosDisponiveis = $qtdeCargosDisponiveis + $cargoUnidade->qtde;

                    if($inputsCargo['qtde'][$i] > $qtdeCargosDisponiveis)
                        return back()->with('error', 'A quantidade de cargos disponíveis é menor do que a quantidade requerida.');

                    $cargoUnidade->qtde = $inputsCargo['qtde'][$i];
                    $cargoUnidade->save();
                }
            }

            DB::commit();
            return back()->with('success', 'Unidade atualizada com sucesso!');
        }catch(\Exception $e){
            DB::rollbak();
            return back()->with('error', 'Erro ao atualizar unidade, favor tente novamente!');
        }

        if($unidade->save())
        {
            for($i = 0; $i < count($inputsCargo['cargo']); $i++)
            {
                if($inputsCargo['cargo'][$i] != null && $inputsCargo['qtde'][$i] != null){
                    $cargoUnidade = CargoUnidade::firstOrNew(['id_cargo' => $inputsCargo['cargo'][$i], 'id_unidade' => $unidade->id]);
                    $qtdeCargosDisponiveis = $this->cargo_unidade->getQtdeCargosDisponiveis($cargoUnidade->id_cargo);
                    $qtdeCargosDisponiveis = $qtdeCargosDisponiveis + $cargoUnidade->qtde;

                    if($inputsCargo['qtde'][$i] > $qtdeCargosDisponiveis)
                        return back()->with('error', 'A quantidade de cargos disponíveis é menor do que a quantidade requerida.');

                    $cargoUnidade->qtde = $inputsCargo['qtde'][$i];
                    $cargoUnidade->save();
                }
            }
            return back()->with('success', 'Unidade atualizada com sucesso!');
        }
        else
        {
            return back()->with('error', 'Erro ao atualizar unidade, favor tente novamente!');
        }

        return redirect('unidade');
    }
    
    public function destroyCargo($id)
    {
        $cargoUnidade = \App\CargoUnidade::find($id);
        if($cargoUnidade->delete()){
            return $cargoUnidade->toJson();
        }
    }

    public function getUnidadesPorOrgao(Request $request)
    {
        $id_orgao_unidade = $request['id_orgao_unidade'];
        $unidades = EstruturaOrganizacional::where('id_orgao_unidade',$id_orgao_unidade)->orderBy('descricao')->get(['id','sigla', 'descricao']);

        return $unidades;
    }

    public function buscaUnidades(Request $request)
    {
        $unidades = $this->estrutura_organizacional->getUnidades($request->id_orgao);
        echo json_encode($unidades);
    }

    public function getUnidadesOrgaoExtinto()
    {
        $this->dadosGridUnidade(1);
    }

}
