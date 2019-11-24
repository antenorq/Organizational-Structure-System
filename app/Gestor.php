<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Repositories\EquipeRepository;

class Gestor extends Model
{
    public $timestamps = false;
    protected $table = 'gestor';
    protected $fillable = ['id_orgao', 'id_cargo', 'nome', 'foto', 'curriculo'];

    public function orgao()
    {
    	return $this->belongsTo('App\EstruturaOrganizacional', 'id_orgao', 'id');
    }

    public function cargo()
    {
    	return $this->belongsTo('App\Cargo', 'id_cargo', 'id');
    }

    public function equipe()
    {
        $equipeRepository = new EquipeRepository;
        return $equipeRepository->getPorOrgao($this->id_orgao);
    }

    public function getGestor($id_orgao)
    {
        $result = DB::table('gestor')
                    ->select('gestor.nome','gestor.foto' ,'estrutura.descricao as orgao', 'cargo.descricao as cargo')
                    ->join('estrutura_organizacional as estrutura', 'estrutura.id', '=', 'gestor.id_orgao')
                    ->join('cargo', 'cargo.id', '=', 'gestor.id_cargo')
                    ->where('gestor.id_orgao', '=', $id_orgao)
                    ->get()
                    ->first();

        return $result;
    }
}
