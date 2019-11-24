<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CargoOrgao extends Model
{
    public $timestamps = false;
    protected $table = 'cargo_orgao';
    protected $fillable = ['id_cargo', 'id_orgao', 'qtde','atribuicao_generica','id_ato_normativo'];

	public function orgao()
	{
		return $this->belongsTo('App\EstruturaOrganizacional', 'id_orgao');
	}

	public function cargo()
	{
		return $this->belongsTo('App\Cargo', 'id_cargo');
	}

    public function getCargoOrgao($id_orgao)
	{
		$result = DB::table('cargo_orgao')
					->select('cargo.descricao', 'cargo_orgao.qtde', 'cargo.grau')
					->join('cargo', 'cargo.id', '=', 'cargo_orgao.id_cargo')
					->where('id_orgao', '=', $id_orgao)
					->get();

	    return $result;
	}

	public function getCargosOrgaos($id_orgao, $tipo_cargo)
	{
		$result = DB::table('cargo_orgao')
					->select('estrutura.descricao as orgao', 'cargo.descricao as cargo', 'cargo.grau', 'cargo_orgao.qtde')
					->join('estrutura_organizacional as estrutura', 'estrutura.id', '=', 'cargo_orgao.id_orgao')
					->join('cargo', 'cargo.id', '=', 'cargo_orgao.id_cargo')
					->where('cargo.id_tipo_cargo', '=', $tipo_cargo)
					->where('estrutura.id_orgao_unidade', '=', $id_orgao)
					->where('estrutura.id_sit_estr_organizacional', '=', 1)
					->orderBy('estrutura.descricao', 'asc')
					->get();

		return $result;
	}

}
