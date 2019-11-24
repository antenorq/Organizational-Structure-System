<?php 

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Historico;
use App\TipoAcaoAtoNormativo;
use App\AtoNormativo;
use App\Http\Helpers;
use DB;

class HistoricoService 
{
	public function salvarDados($model)
	{
		$historico = new Historico;
		
		DB::beginTransaction();
		try {
			$historico->id_est_organizacional = $model->id;
			$historico->sigla = $model->sigla;
			$historico->descricao = $model->descricao;
			$historico->id_ato_normativo = $model->id_ato_normativo;
			$historico->id_tipo_acao_ato_normativo = $model->id_tipo_acao_ato_normativo;
			$historico->competencia = $model->competencia;
			$historico->finalidade = $model->finalidade;
			$historico->id_usuario = Auth::user()->id;
			$historico->data = date('Ymd');
			$historico->save();		
			DB::commit();		
		}catch(\Exception $e){
			DB::rollback();
		}		
	}
}

?>