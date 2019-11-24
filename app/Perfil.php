<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = "perfil";

    public function getPerfil()
    {
    	$result = $this->pluck('descricao', 'id');
    	return $result;
    }
}
