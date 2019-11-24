<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrgaoColegiadoRequest;
use Illuminate\Support\Facades\Auth;

use App\EstruturaOrganizacional;
use App\Funcao;
use App\Situacao;
use App\AtoNormativo;
use App\TipoAcaoAtoNormativo;
use App\TipoHierarquia;
use App\Services\HistoricoService;
use App\Http\Helpers;
use DB;
use Yajra\Datatables\Datatables;


class OrgaoColegiadoController extends Controller
{
    public $estrutura_organizacional;
    public $ato;

    public function __construct()
    {
        $this->middleware('auth');
        $this->estrutura_organizacional = new EstruturaOrganizacional;
        $this->ato = new AtoNormativo;
        $this->helper = new Helpers;
    }

    public function index()
    {
        return view('orgaocolegiado.index');
    }

    public function dadosGridOrgaoColegiado()
    {
        $orgaocolegiados = EstruturaOrganizacional::with('funcao','situacao','orgaoColegiadoRel','tipoAcaoAtoNormativo')->where('id_tipo_estrutura',3)->get();

        //APENAS EXIBE OS ÓRGÃOS COLEGIADOS ATIVOS E PENDENTES
        $orgaocolegiados = $orgaocolegiados->whereIn('id_sit_estr_organizacional', [1, 3]);

        return Datatables::of($orgaocolegiados)
            ->addColumn('action', function ($orgaocolegiado)
            {
                return '
                    <a href="'.route('orgaocolegiado.show', ['id'=>$orgaocolegiado->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-search"></i> Exibir</a>
                    <a href="'.route('orgaocolegiado.edit', ['id'=>$orgaocolegiado->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Editar</a>
                ';

            })
            ->make(true);
    }

    public function create()
    {
        $permissao = $this->helper->validarAcessoCreate();

        if ($permissao)
            return redirect('orgaocolegiado')->with('status', $permissao['mensagem']);

        $funcoes = Funcao::orderBy('descricao')->pluck('descricao', 'id');
        $tiposAcaoAtoNormativo = TipoAcaoAtoNormativo::whereIn('id', [1,2,5])->pluck('descricao', 'id');
        $tiposHierarquia = TipoHierarquia::where('id_tipo_estrutura',3)->pluck('descricao', 'id');
        $orgaos = $this->estrutura_organizacional->getOrgaos();

        //Caso o usuário tenha o perfil adm ou aprovador, o combo conterá as opções ativo e pendente
        if(Auth::user()->id_perfil == 1 || Auth::user()->id_perfil == 2)
            $situacoes = Situacao::whereIn('id', [1, 3])->orderBy('descricao')->pluck('descricao', 'id');
        else
            $situacoes = Situacao::where('id', 3)->orderBy('descricao')->pluck('descricao', 'id');

        return view('orgaocolegiado.create',['funcoes'=>$funcoes,'situacoes'=>$situacoes,'tiposAcaoAtoNormativo'=>$tiposAcaoAtoNormativo,'orgaos'=>$orgaos,'tiposHierarquia'=>$tiposHierarquia]);
    }

    public function store(OrgaoColegiadoRequest $request)
    {
        //Campo do tipo CLOB por causa de miséra do oracle tem que inserir no final do INSERT
        //Por isso foi dado o Except nos campos CLOB ('competencia','finalidade') para inserir por último.
        $inputs_orgaocolegiado = $request->except('competencia','finalidade');     
        
        $historico = new HistoricoService();
        $orgaocolegiado = new EstruturaOrganizacional();

        //Preenche no Model os valores que veio do POST Formulário
        $orgaocolegiado->fill($inputs_orgaocolegiado);

        //Força esse valor pois sei que esse create é do tipo Conselho
        $orgaocolegiado->id_tipo_estrutura = 3; // Estrutura Conselho
        $orgaocolegiado->id_tipo_hierarquia = 8; // Hierarquia Conselho

        //Formata o campo data fim para o oracle
        $orgaocolegiado->data_fim = $this->helper->date_db_format($orgaocolegiado->data_fim);

        $orgaocolegiado->competencia = $request->competencia;
        $orgaocolegiado->finalidade = $request->finalidade;

        DB::beginTransaction();
        try {
            //Salva órgão colegiado
            $orgaocolegiado->save();
            //Salva histórico se o perfil do usuário for adm
            if(Auth::user()->id_perfil == 1)
                $historico->salvarDados($orgaocolegiado);

            DB::commit();
            return back()->with('success', 'Órgao Colegiado cadastrado com sucesso!');
        }catch(\Exception $e){
            DB::rollback();
            return back()->with('error', 'Erro ao cadastrar órgão colegiado, favor tente novamente!');
        }

        return redirect('orgaocolegiado');
    }

    public function show($id)
    {
        $orgao_colegiado = $this->estrutura_organizacional->getShowOrgaoColegiado($id);
        $orgao_colegiado->data = $this->helper->date_system_format_oracle($orgao_colegiado->data);
        $orgao_colegiado->data_fim = $this->helper->date_system_format_oracle($orgao_colegiado->data_fim);

        $orgao_relacionado = null;

        //Busca a unidade subordinada
        if($orgao_colegiado->id_orgao_orgaocolegiado)
            $orgao_relacionado = $this->estrutura_organizacional->getHierarquia($orgao_colegiado->id_orgao_orgaocolegiado);

        //Busca as situações diferente de pendente
        $situacoes = Situacao::where('id', '!=', 3)->pluck('descricao', 'id');

        return view('orgaocolegiado.show', ['orgao_colegiado'=>$orgao_colegiado, 'orgao_relacionado'=>$orgao_relacionado, 'situacoes'=>$situacoes]);
    }

    public function edit($id)
    {
        $objetoOrgaoColegiado = EstruturaOrganizacional::find($id);
        $situacao = $objetoOrgaoColegiado->id_sit_estr_organizacional;

        $permissao = $this->helper->validarAcessoEdit($situacao);

        if ($permissao)
            return redirect('orgaocolegiado')->with('status', $permissao['mensagem']);

        $orgaocolegiado = EstruturaOrganizacional::find($id);
        $funcoes = Funcao::orderBy('descricao')->pluck('descricao', 'id');
        $situacoes = Situacao::orderBy('descricao')->pluck('descricao', 'id');
        $tiposAcaoAtoNormativo = TipoAcaoAtoNormativo::whereIn('id', [1,2,5,7])->pluck('descricao', 'id');
        $tiposHierarquia = TipoHierarquia::where('id_tipo_estrutura',3)->pluck('descricao', 'id');
        $orgaos = $this->estrutura_organizacional->getOrgaos();

        $orgaocolegiado->data_fim = $this->helper->date_system_format_oracle($orgaocolegiado->data_fim);

        //Caso o usuário tenha o perfil adm ou aprovador, o combo conterá as opções ativo, extinto e pendente
        if(Auth::user()->id_perfil == 1 || Auth::user()->id_perfil == 2)
            $situacoes = Situacao::whereIn('id', [1, 3, 4])->orderBy('descricao')->pluck('descricao', 'id');
        else
            $situacoes = Situacao::where('id', 3)->orderBy('descricao')->pluck('descricao', 'id');

        //Busca o ato normativo do órgão colegiado para exibir no campo número do ato
        $ato_normativo = $this->ato->getAto($orgaocolegiado->id_ato_normativo);
        foreach($ato_normativo as $ato)
        {
            $ato = $ato->tipo .' - '. $ato->numero .' - '. $this->helper->date_system_format_oracle($ato->data);
        }

        return view('orgaocolegiado.edit',['orgaocolegiado'=>$orgaocolegiado,'funcoes'=>$funcoes,'situacoes'=>$situacoes,'tiposAcaoAtoNormativo'=>$tiposAcaoAtoNormativo,'orgaos'=>$orgaos,'tiposHierarquia'=>$tiposHierarquia, 'ato'=>$ato]);
    }

    public function update(OrgaoColegiadoRequest $request, $id)
    {
        $orgaocolegiado = EstruturaOrganizacional::find($id);
        $historico = new HistoricoService();

        $inputs_orgaocolegiado = $request->all();

        //Preenche no Model os valores que veio do POST Formulário
        $orgaocolegiado->fill($inputs_orgaocolegiado);

        //Força esse valor pois sei que esse create é do tipo Órgão Colegiado
        $orgaocolegiado->id_tipo_estrutura = 3; // Estrutura órgão colegiado

        //Formata o campo data fim para o oracle
        $orgaocolegiado->data_fim = $this->helper->date_db_format($orgaocolegiado->data_fim);

        DB::beginTransaction();
        try {
            //Salva orgao colegiado
            $orgaocolegiado->save();
            //Salva histórico se o perfil do usuário for adm
            if(Auth::user()->id_perfil == 1)
                $historico->salvarDados($orgaocolegiado);

            DB::commit();
            return back()->with('success', 'Órgao Colegiado cadastrado com sucesso!');
        }catch(\Exception $e){
            DB::rollbak();
            return back()->with('error', 'Erro ao cadastrar órgão colegiado, favor tente novamente!');
        }

        if($orgaocolegiado->save())
        {
            return back()->with('success', 'Órgão Colegiado atualizado com sucesso!');
        }
        else
        {
            return back()->with('error', 'Erro ao atualizar, favor tente novamente!');
        }

        return redirect('orgaocolegiado');
    }

}
