<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoAtoNormativo extends Model
{
    protected $table = 'tipo_ato_normativo';

    public function getTipo($id)
    {
    	return $this->where('id', $id)->pluck('descricao')->first();
    }
}
