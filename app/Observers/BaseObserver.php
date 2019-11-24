<?php
namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Auditoria;

class BaseObserver
{
	// 
	public function created(Model $model)
	{
		$auditoria = new Auditoria;
		
		$auditoria->id_usuario 		= Auth::user()->getKey();
		$auditoria->id_registro 	= $model->getKey();
		$auditoria->acao 			= Route::currentRouteName();
		$auditoria->nome_tabela 	= $model->getTable();
		$auditoria->ip 				= $_SERVER['REMOTE_ADDR'];

		$auditoria->save();		
	}	

	// 
	public function updated(Model $model)
	{		
		if(Auth::User() != null)
		{
			$dataDiff = $this->arrayDiff($model->getOriginal(), $model->getAttributes());
			
			foreach($dataDiff as $attribute => $attribute_value) {
			$dataOriginal = $model->getOriginal();
			
			$auditoria = new Auditoria;
			
			$auditoria->id_usuario 		= Auth::user()->getKey();
			$auditoria->id_registro 	= $model->getKey();
			$auditoria->acao 			= Route::currentRouteName();
			$auditoria->atributo 		= $attribute;
			$auditoria->valor_antigo 	= $dataOriginal[$attribute];
			$auditoria->nome_tabela 	= $model->getTable();
			$auditoria->ip 				= $_SERVER['REMOTE_ADDR'];

			$auditoria->save();
			}	
		}	
	}	
	
	// 
	public function deleted(Model $model)
	{
		$auditoria = new Auditoria;
		
		$auditoria->id_usuario 		= Auth::user()->getKey();
		$auditoria->id_registro 	= $model->getKey();
		$auditoria->acao 			= Route::currentRouteName();
		$auditoria->nome_tabela 	= $model->getTable();
		$auditoria->ip 				= $_SERVER['REMOTE_ADDR'];

		$auditoria->save();		
	}	
	
	// 
	public function arrayDiff($array_1, $array_2)
	{
		$dataDiff = [];
		foreach($array_1 as $key => $value) {
			if (array_key_exists($key, $array_2)) {
				if ($value != $array_2[$key]) {
					$dataDiff[$key] = $array_2[$key];
				}
			}
			else {
				$dataDiff[$key] = $value;
			}
		}
	
		return $dataDiff;
	}	

}