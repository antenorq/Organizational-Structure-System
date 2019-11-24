<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Cargo;

class CargoUnidade extends Model
{
    public $timestamps = false;
    protected $table = 'cargo_unidade';
    protected $fillable = ['id_cargo', 'id_unidade', 'qtde'];

	public function unidade()
	{
		return $this->belongsTo('App\EstruturaOrganizacional', 'id_unidade');
	}

	public function cargo()
	{
		return $this->belongsTo('App\Cargo', 'id_cargo');
	}

    public function getCargoUnidade($id_unidade)
	{
		$result = DB::table('cargo_unidade')
					->select('cargo.descricao', 'cargo_unidade.qtde', 'cargo.grau')
					->join('cargo', 'cargo.id', '=', 'cargo_unidade.id_cargo')
					->where('id_unidade', '=', $id_unidade)
					->get();

	    return $result;
	}

	public function getCargosUnidades($id_orgao, $tipo_cargo)
	{
		$result = DB::table('cargo_unidade')
					->select('estrutura.descricao as unidade', 'cargo.descricao as cargo', 'cargo.grau', 'cargo_unidade.qtde')
					->join('estrutura_organizacional as estrutura', 'estrutura.id', '=', 'cargo_unidade.id_unidade')
					->join('cargo', 'cargo.id', '=', 'cargo_unidade.id_cargo')
					->where('cargo.id_tipo_cargo', '=', $tipo_cargo)
					->where('estrutura.id_orgao_unidade', '=', $id_orgao)
					->where('estrutura.id_sit_estr_organizacional', '=', 1)
					->orderBy('estrutura.descricao', 'asc')
					->get();

		return $result;
	}

	public function getQtdeCargosDisponiveis($id_cargo)
	{
		$qtdeCargosCriados =
			DB::table('cargo')
				->select('qtde')
				->where('id', '=', $id_cargo)
				->pluck('qtde')->first();

		$qtdeCargosAlocados =
			DB::table('cargo_unidade')
				->select(DB::raw('sum(qtde) as soma'))
				->where('id_cargo', '=', $id_cargo)
				->pluck('soma')->first();

		$qtd = $qtdeCargosCriados - $qtdeCargosAlocados;
		return $qtd <= 0 ? 0 : $qtd;
	}

}
