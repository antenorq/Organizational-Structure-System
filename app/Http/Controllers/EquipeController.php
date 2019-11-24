<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\EstruturaOrganizacional;
use App\Http\Requests\EquipeRequest;
use App\Services\EquipeService;
use App\Http\Helpers;
use App\Gestor;
use App\Equipe;
use App\Cargo;
use Log;
use DB;

class EquipeController extends Controller
{
    public $estrutura;
    public $equipe;
    public $gestor;
    public $cargo;

    public function __construct()
    {
        $this->middleware('auth');        
    }

    public function index()
    {
        return view('equipe.index');
    }

    public function gridEquipe()
    {
        $gestores = Gestor::with('orgao', 'cargo')->get();

        return Datatables::of($gestores)
                ->addColumn('action', function ($gestor)
                {
                    return '
                        <a href="'.route('equipe.show', ['id'=>$gestor->id_orgao]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-search"></i> Exibir</a>
                        <a href="'.route('equipe.edit', ['id'=>$gestor->id_orgao]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Editar</a>
                    ';
                })
                ->make(true);
    }

    public function create()
    {
        $gestor = new Gestor;
        $estrutura = new EstruturaOrganizacional;
        $cargo = new Cargo;
        $helper = new Helpers;

        $permissao = $helper->validarAcessoCreate();

        if ($permissao)
            return redirect('equipe')->with('status', $permissao['mensagem']);
        
        $orgaos = $estrutura->getOrgaos();
        $unidades = $estrutura->getUnidades();
        $cargos = $cargo->getCargo();

        if(!empty(old('equipe.nomes')))
            foreach(old('equipe.nomes') as $key => $value)
                $equipe[] = new \App\Equipe(['nome' => old('nome.'.$value), 'id_unidade' => old('equipe.unidades.'.$key), 'id_cargo' => old('equipe.cargos.'.$key)]);
        else
            $equipe[] = new Equipe;  

        $select_default = [null => 'Selecione o cargo'];
        $cargos = $select_default + $cargos->toArray();

        return view('equipe.create', ['estruturasOrgao'=>$orgaos, 'cargos'=>$cargos, 'unidades' => $unidades, 'gestor' => $gestor, 'equipe' => $equipe]);
    }

    public function store(EquipeRequest $request)
    {
        $data = $request->all();
        $equipe = $request->only('equipe');

        $service = new EquipeService;
        $service->salvar($data['gestor'], $equipe);
    
        return back()->with('success', 'Equipe cadastrada com sucesso!');
    }

    public function show($id_orgao)
    {
        $gestor = new Gestor;
        $equipe = new Equipe;

        $gestor = $gestor->getGestor($id_orgao);
        $equipe = $equipe->getEquipe($id_orgao);

        return view('equipe.show', ['gestor'=>$gestor, 'equipe'=>$equipe]);
    }

    public function edit($id)
    {
        $gestor = new Gestor;
        $estrutura = new EstruturaOrganizacional;
        $cargo = new Cargo;
        $equipe = new Equipe;
        $helper = new Helpers;

        $permissao = $helper->validarAcessoEdit();

        if ($permissao)
            return redirect('equipe')->with('status', $permissao['mensagem']);
        

        $gestor = $gestor->where('id_orgao', $id)->get()->first();

        $equipe = $equipe->getEquipeEdit($id);
        $orgaos = $estrutura->getOrgaos();
        $unidades = $estrutura->getUnidades();
        $cargos = $cargo->getCargo();
        $equipe = $gestor->equipe();

        //GESTOR
        $data['gestor']['nome'] = $gestor->nome;
        $data['gestor']['id_cargo'] = $gestor->id_cargo;
        $data['gestor']['id_orgao'] = $gestor->id_cargo;

        return view('equipe.edit', ['gestor'=>$gestor, 'estruturasOrgao'=>$orgaos, 'cargos'=>$cargos, 'unidades' => $unidades, 'equipe' => $equipe]);
    }

    public function update(EquipeRequest $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        dd($data);

        $service = new EquipeService;
        $service->salvar($data['gestor'], $equipe);

        try{
            //Efetua upload da foto
            if(isset($data['gestor']['foto'])){
                $caminho = $this->upload($data['gestor']['foto']);
                $data['gestor']['foto'] = $caminho;
            }

            $data = $equipeService->salvarDados($data);
            return redirect(route('equipe.edit',['id' => $id]))->with('success', 'Equipe atualizada com sucesso!');
        }catch(\Exception $e){
            Log::error($e->getMessage(), [$e]);
            return back()->with('error', 'Houve em erro ao tentar salvar, por favor tente novamente!');
        }
    }

    public function upload($file)
    {
        $nameFile = date('Y-m-d-H-i-s_') . $file->getClientOriginalName();

        $file->move(public_path() . "/fotos_gestor/", $nameFile);
        $caminho = "/fotos_gestor/". $nameFile;

        return $caminho;
    }

    public function destroy($id)
    {
        $equipe = Equipe::find($id);
        if($equipe->delete()){
            return $equipe->toJson();
        }
    }
}
