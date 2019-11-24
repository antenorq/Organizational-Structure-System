<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
	protected $table = 'endereco';
	public $timestamps = false;	
	protected $fillable = ['id','numero','logradouro','complemento','cep','bairro'];
}
