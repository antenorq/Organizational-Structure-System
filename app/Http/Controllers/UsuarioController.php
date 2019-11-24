<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UsuarioRequest;
use App\Http\Requests\UsuarioUpdateRequest;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\EstruturaOrganizacional;
use App\Perfil;
use App\Http\Helpers;
use App\Mail\EnviarEmail;
use Mail;
use DB;
use Yajra\Datatables\Datatables;


class UsuarioController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['recuperarSenha', 'updatePassword']);
        $this->user = new User;
        $this->helper = new Helpers;
        $this->perfil = new Perfil;
        $this->middleware('ValidarAdministrador')->except(['dadosUsuario', 'atualizarDadosUsuario']);
        $this->estrutura_organizacional = new EstruturaOrganizacional;
    }

    public function index()
    {
        return view('usuario.index');
    }

    public function gridUsuario()
    {
        $usuarios = User::with('orgao', 'perfil')->get();

        return Datatables::of($usuarios)
            ->addColumn('action', function ($usuario)
            {
                return '
                    <a href="'.route('usuario.show', ['id'=>$usuario->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-search"></i> Exibir</a>
                    <a href="'.route('usuario.edit', ['id'=>$usuario->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Editar</a>
                    <a href="'.route('usuario.destroy', ['id'=>$usuario->id]).'" class="btn btn-xs btn-danger delete"><i class="glyphicon glyphicon-remove"></i> Excluir</a>
                ';
        })
        ->make(true);
    }

    public function create()
    {
        $orgaos = $this->estrutura_organizacional->getOrgaos(); 
        $perfils = \App\Perfil::all()->pluck('descricao', 'id');

        return view('usuario.create', ['orgaos'=>$orgaos, 'perfils' => $perfils]);
    }

    public function store(UsuarioRequest $request)
    {
        $data = $request->all();
    
        $usuario = new User;
        $usuario->fill($data);
        $usuario->password = bcrypt($data['password']);
        
        if($usuario->save())
            return back()->with('success', 'Usuário cadastrado com sucesso!');
        else
            return back()->with('error', 'Houve em erro ao tentar salvar, por favor tente novamente!');
    }

    public function show($id)
    {
        $usuario = User::find($id);
        $estrutura = $this->estrutura_organizacional::find($usuario->id_orgao);
        $perfil = $this->perfil::find($usuario->id_perfil);
        
        $usuario->orgao = $estrutura->descricao;
        $usuario->perfil = $perfil->descricao;
        $usuario->data_criacao = $this->helper->datetime_system_format($usuario->created_at);

        return view('usuario.show', ['usuario' => $usuario]);
    }
        
    public function edit($id)
    {
        $usuario = User::find($id);
        $orgaos = $this->estrutura_organizacional->getOrgaos(); 
        $perfils = \App\Perfil::all()->pluck('descricao', 'id');

        return view('usuario.edit', ['usuario' => $usuario, 'orgaos'=>$orgaos, 'perfils' => $perfils]);
    }

    public function update(UsuarioRequest $request, $id)
    {
        $data = $request->all();

        $usuario = User::find($id);
        $usuario->fill($data);
        
        if($usuario->save())
            return back()->with('success', 'Usuário atualizado com sucesso!');
        else
            return back()->with('error', 'Houve em erro ao tentar salvar, por favor tente novamente!');   
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
    }

    public function dadosUsuario()
    {
        $usuario = User::find(Auth::user()->id);

        return view('usuario.dados-usuario', ['usuario'=>$usuario]);
    }

    public function atualizarDadosUsuario(UsuarioUpdateRequest $request)
    {
        $usuario = User::find(Auth::user()->id);
        $usuario->fill($request->all());
        $usuario->password = bcrypt($request->password);
               
        if($usuario->save())
            return back()->with('success', 'Usuário atualizado com sucesso');
        else
            return back()->with('error', 'Houve em erro ao tentar salvar, por favor tente novamente!');          
    }

    public function recuperarSenha()
    {   
        return view('usuario.recuperar-senha');
    }

    public function updatePassword(Request $request)
    {
        $result = $this->verificaEmail($request->email);

        if(!$result->isEmpty())
            $senhaGerada = $this->novaSenha($result);
        else
            return back()->with('error', 'E-mail não cadastrado');    

        $config = ["assunto"=>"SIGEO - Nova Senha", "view"=>"email.nova_senha", "senha"=>$senhaGerada, "email"=>$result[0]->email];
        $this->enviarEmail($config);

        return back()->with('success', 'Enviado e-mail com a nova senha');
    }

    public function novaSenha($result)
    {
        $senhaGerada = $this->geraSenha();
        $email = $result[0]->email;

        $usuario = $this->user->where('email', $email)->first();
        $usuario->password = bcrypt($senhaGerada);
        $usuario->save();

        return $senhaGerada;
    }

    public function geraSenha()
    {
        //Determina os caracteres que conterão o token
        $caracteres = "0123456789abcdefghijklmnopqrstuvwxyz#@$!*";
        //Embaralha os caracteres e pega apenas os 6 primeiros
        $string  = substr(str_shuffle($caracteres), 0, 6);

        return $string;        
    }

    public function verificaEmail($email)
    {
        $result = DB::table('users')
                    ->where('email', '=', $email)
                    ->get();

        return $result;
    }

    public function enviarEmail($config) {
        $vars = [];
            $vars['assunto'] = $config['assunto'];
            $vars['view'] = $config['view'];
            $vars['vars'] = [
                'senha' => $config['senha'],
            ];

        Mail::to($config['email'])->send(new EnviarEmail($vars));
    }
}
