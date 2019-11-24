<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cargo extends Model
{
    public $timestamps = false;
    protected $table = 'cargo';    
    protected $fillable = ['id_tipo_cargo', 'id_ato_normativo', 'id_tipo_acao_ato_normativo', 'descricao', 'qtde', 'grau', 'obs_ato_normativo', 'atribuicao'];

    public function tipo()
    {
    	return $this->belongsTo('App\TipoCargo', 'id_tipo_cargo', 'id');
    }

    public function situacao()
    {
        return $this->belongsTo('App\Situacao', 'id_situacao', 'id');
    }

    public function getCargo()
    {
        $result = $this->all()->pluck('descricao', 'id');
        return $result;
    }
    
    public function cargosOrgao()
	{
		return $this->hasMany('App\CargoOrgao','id_cargo');
    }
    
    public function getCargoShow($id)
    {
        $result = DB::table('cargo')
                ->select('cargo.id', 'cargo.descricao as cargo_descricao', 'tipo.descricao as tipo_descricao', 'cargo.qtde', 'cargo.grau', 'cargo.obs_ato_normativo', 'cargo.id_situacao', 'cargo.atribuicao', 'ato.numero', 'tipoato.descricao as tipoato_descricao', 'ato.data', 'tipoacaoato.descricao as acaoato_descricao')
                ->join('tipo_cargo as tipo', 'tipo.id', '=', 'cargo.id_tipo_cargo')
                ->join('ato_normativo as ato', 'ato.id', '=', 'cargo.id_ato_normativo')
                ->join('tipo_ato_normativo as tipoato', 'tipoato.id', '=', 'ato.id_tipo_ato_normativo')
                ->join('tipo_acao_ato_normativo as tipoacaoato', 'tipoacaoato.id', '=', 'cargo.id_tipo_acao_ato_normativo')
                ->where('cargo.id', '=', $id)
                ->get()
                ->first();

        return $result;
    }
    

}
