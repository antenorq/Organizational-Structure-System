<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoAcaoAtoNormativo extends Model
{
    protected $table = 'tipo_acao_ato_normativo';

     public function getAcao($id_tipo_acao_ato_normativo)
    {
    	return $this->where('id', $id_tipo_acao_ato_normativo)->get()->pluck('descricao')->first();
    }
}
