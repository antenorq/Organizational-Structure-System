<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Auditoria extends Model
{
    public $timestamps 	= false;
    protected $table 	= 'auditoria';
    protected $fillable = ['id_usuario', 'id_registro', 'acao', 'atributo', 'valor_antigo', 'nome_tabela', 'ip', 'data_hora'];
}
