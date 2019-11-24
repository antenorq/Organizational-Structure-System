<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OrgaoRequest;

use App\EstruturaOrganizacional;
use App\Funcao;
use App\Situacao;
use App\AtoNormativo;
use App\TipoAcaoAtoNormativo;
use App\TipoHierarquia;
use App\Endereco;
use App\Services\HistoricoService;
use App\Http\Helpers;
use Yajra\Datatables\Datatables;
use DB;

class OrgaoController extends Controller
{
    public $estrutura_organizacional;
    public $ato;
    public $helper;

    public function __construct()
    {
        $this->middleware('auth');
        $this->estrutura_organizacional = new EstruturaOrganizacional;
        $this->ato = new AtoNormativo;
        $this->helper = new Helpers;
    }

    public function index()
    {
        return view('orgao.index');
    }

    public function dadosGridOrgao()
    {
        $orgaos = EstruturaOrganizacional::where('id_tipo_estrutura',1)->with('tipoHierarquia','situacao','tipoAcaoAtoNormativo')->get();

        //APENAS EXIBE OS ÓRGÃOS ATIVOS E PENDENTES
        //$orgaos = $orgaos->whereIn('id_sit_estr_organizacional', [1, 3]);

        return Datatables::of($orgaos)
            ->addColumn('action', function ($orgao)
            {
                return '
                    <a href="'.route('orgao.show', ['id'=>$orgao->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-search"></i> Exibir</a>
                    <a href="'.route('orgao.edit', ['id'=>$orgao->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Editar</a>
                ';
        })
        ->make(true);
    }

    public function create()
    {
        $permissao = $this->helper->validarAcessoCreate();

        if ($permissao)
            return redirect('orgao')->with('status', $permissao['mensagem']);

        $funcoes = Funcao::orderBy('descricao')->pluck('descricao', 'id');
        $tiposAcaoAtoNormativo = TipoAcaoAtoNormativo::whereIn('id', [1,2,5])->pluck('descricao', 'id');
        $tiposHierarquia = TipoHierarquia::where('id_tipo_estrutura',1)->orderBy('descricao')->pluck('descricao', 'id');
        $orgaos = $this->estrutura_organizacional->getOrgaos();
        $unidades_representacao = $this->estrutura_organizacional->getUnidadesRepresentacao();


        //Caso o usuário tenha o perfil adm ou aprovador, o combo conterá as opções ativo e pendente
        if(Auth::user()->id_perfil == 1 || Auth::user()->id_perfil == 2)
            $situacoes = Situacao::whereIn('id', [1, 3])->orderBy('descricao')->pluck('descricao', 'id');
        else
            $situacoes = Situacao::where('id', 3)->orderBy('descricao')->pluck('descricao', 'id');

        return view('orgao.create',['funcoes'=>$funcoes,
                                    'situacoes'=>$situacoes,
                                    'tiposAcaoAtoNormativo'=>$tiposAcaoAtoNormativo,
                                    'tiposHierarquia'=>$tiposHierarquia,
                                    'orgaos'=>$orgaos,
                                    'unidades_representacao'=>$unidades_representacao]);
    }

    public function store(OrgaoRequest $request)
    {

        //Campo do tipo CLOB por causa de miséra do oracle tem que inserir no final do INSERT
        //Por isso foi dado o Except nos campos CLOB ('competencia','finalidade') para inserir por último.
        $inputs_orgao = $request->except('competencia','finalidade');         
        $inputs_endereco = $inputs_orgao['endereco'];

        $orgao = new EstruturaOrganizacional();
        $endereco = new Endereco();
        $historico = new HistoricoService();

        //Preenche no Model os valores que veio do POST Formulário
        $orgao->fill($inputs_orgao);
        $endereco->fill($inputs_endereco);
        
        //$orgao->data_fim = $this->helper->date_db_format($orgao->data_fim);
        $orgao->horario_funcionamento = $inputs_orgao['hora_inicio'] . " - " .  $inputs_orgao['hora_fim'];
        
        //Formata o campo data fim para o oracle
        $orgao->data_fim = $this->helper->date_db_format($orgao->data_fim);

        $orgao->competencia = $request->competencia;
        $orgao->finalidade = $request->finalidade;


        DB::beginTransaction();
        try {
            //Salva endereço
            $endereco->save();
            //Salva órgão
            $orgao->id_tipo_estrutura = 1; 
            $orgao->id_endereco = $endereco->id;
            $orgao->save();
            //Salva histórico se o perfil do usuário for adm
            if(Auth::user()->id_perfil == 1)
                $historico->salvarDados($orgao);

            DB::commit();
            return back()->with('success', 'Orgão cadastrado com sucesso!');
        }catch(\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erro ao cadastrar órgão, favor tente novamente!');
        }

    }

    public function show($id)
    {
        $orgao = $this->estrutura_organizacional->getShowOrgao($id);
        $orgao->data = $this->helper->date_system_format_oracle($orgao->data);
        $orgao->data_fim = $this->helper->date_system_format_oracle($orgao->data_fim);

        $vinculacao = array();
        $unidade_representacao = array();

        //Busca a vinculação ou a unidade representada do órgão
        if($orgao->id_orgao_vinculacao)
            $vinculacao = $this->estrutura_organizacional->getHierarquia($orgao->id_orgao_vinculacao);

        if($orgao->id_unidade_representacao)
            $unidade_representacao = $this->estrutura_organizacional->getHierarquia($orgao->id_unidade_representacao);

        //Busca as situações diferente de pendente
        $situacoes = Situacao::whereIn('id', [1,4])->pluck('descricao', 'id');

        return view('orgao.show', ['orgao'=>$orgao, 'vinculacao'=>$vinculacao, 'unidade_representacao'=>$unidade_representacao, 'situacoes'=>$situacoes]);
    }

    public function edit($id)
    {
        $orgao = EstruturaOrganizacional::find($id);
        $situacao = $orgao->id_sit_estr_organizacional;

        $permissao = $this->helper->validarAcessoEdit($situacao);

        if ($permissao)
            return redirect('orgao')->with('status', $permissao['mensagem']);
        
        $endereco = Endereco::find($orgao->id_endereco);
        $funcoes = Funcao::orderBy('descricao')->pluck('descricao', 'id');
        $situacoes = Situacao::orderBy('descricao')->pluck('descricao', 'id');
        $tiposAcaoAtoNormativo = TipoAcaoAtoNormativo::whereIn('id', [1,2,5,7])->pluck('descricao', 'id');
        $tiposHierarquia = TipoHierarquia::where('id_tipo_estrutura',1)->pluck('descricao', 'id');
        $orgaos = $this->estrutura_organizacional->getOrgaos();
        $unidades_representacao = $this->estrutura_organizacional->getUnidadesRepresentacao();

        $orgao['hora_inicio'] = $this->helper->system_format($orgao['horario_funcionamento'], "horario_funcionamento_inicio");
        $orgao['hora_fim'] = $this->helper->system_format($orgao['horario_funcionamento'], "horario_funcionamento_fim");
        $orgao->data_fim = $this->helper->date_system_format_oracle($orgao->data_fim);
        $orgao->cnpj = $this->helper->remove_cnpj($orgao->cnpj);

        //Caso o usuário tenha o perfil adm ou aprovador, o combo conterá as opções ativo, extinto e pendente
        if(Auth::user()->id_perfil == 1 || Auth::user()->id_perfil == 2)
            $situacoes = Situacao::whereIn('id', [1, 3, 4])->orderBy('descricao')->pluck('descricao', 'id');
        else
            $situacoes = Situacao::where('id', 3)->orderBy('descricao')->pluck('descricao', 'id');

        //Busca o ato normativo do órgão para exibir no campo número do ato
        $ato_normativo = $this->ato->getAto($orgao->id_ato_normativo);
        foreach($ato_normativo as $ato)
        {
            $ato = $ato->tipo .' - '. $ato->numero .' - '. $this->helper->date_system_format_oracle($ato->data);
        }

        return view('orgao.edit',[  'orgao'=>$orgao,
                                    'endereco'=>$endereco,
                                    'funcoes'=>$funcoes,
                                    'situacoes'=>$situacoes,
                                    'tiposAcaoAtoNormativo'=>$tiposAcaoAtoNormativo,
                                    'tiposHierarquia'=>$tiposHierarquia,
                                    'orgaos'=>$orgaos,
                                    'unidades_representacao'=>$unidades_representacao,
                                    'ato'=>$ato]);
    }

    public function update(OrgaoRequest $request, $id)
    {
        $orgao = EstruturaOrganizacional::find($id);
        $endereco = new Endereco();
        $historico = new HistoricoService();

        $inputs_orgao = $request->all();
        $inputs_endereco = $inputs_orgao['endereco'];

        //Preenche no Model os valores que veio do POST Formulário
        $orgao->fill($inputs_orgao);
        $orgao->data_fim = $this->helper->date_db_format($orgao->data_fim);

        $endereco->fill($inputs_endereco);

        DB::beginTransaction();
        try {
            //Salva endereço
            //$endereco->save();
            //Salva órgão
            $orgao->id_tipo_estrutura = 1; // Estrutura Orgao
            $orgao->id_endereco = $endereco->id;
            $orgao->save();
            //Salva histórico se o perfil do usuário for adm
            if(Auth::user()->id_perfil == 1)
                $historico->salvarDados($orgao);

            DB::commit();
            return back()->with('success', 'Orgão atualizado com sucesso!');
        }catch(\Exception $e){
            DB::rollback();
            return back()->with('error', 'Erro ao atualizar órgão, favor tente novamente!');
        }
    }

    public function getOrgaos()
    {
        $orgaos = $this->estrutura_organizacional->getOrgaos();        
        return json_encode($orgaos);
    }

}
