<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\EquipeRepository;
use DB;

class Equipe extends Model
{
    public $timestamps = false;
    protected $table = 'equipe';
    protected $fillable = ['id_cargo', 'id_unidade', 'nome'];

    public function getEquipe($id_orgao)
    {
        $repo = new EquipeRepository;
        return $repo->getPorOrgao($id_orgao);
    }

    public function getEquipeEdit($id_orgao)
    {
        $result = DB::table('equipe')
                    ->select('equipe.id', 'equipe.nome', 'estrutura.id as id_unidade', 'cargo.id as id_cargo')
                    ->join('cargo', 'cargo.id', '=', 'equipe.id_cargo')
                    ->join('estrutura_organizacional as estrutura', 'estrutura.id', '=', 'equipe.id_unidade')
                    ->where('estrutura.id_orgao_unidade', '=', $id_orgao)
                    ->orderBy('estrutura.descricao')
                    ->get();

        return $result;
    }

}
