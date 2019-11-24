<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Funcao extends Model
{
    protected $table = 'funcao';
    public $timestamps = false;	

    public function EstruturaOrganizacional()
	{
		return $this->belongsTo('App\EstruturaOrganizacional');
	}
}




