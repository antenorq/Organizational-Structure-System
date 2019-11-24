<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EstruturaOrganizacional;
use App\AtoNormativo;
use App\TipoAcaoAtoNormativo;
use App\TipoAtoNormativo;
use DB;

class Historico extends Model
{
    public $timestamps = false;
    protected $table = 'historico';
    protected $fillable = ['id_est_organizacional', 'sigla', 'descricao', 'id_tipo_acao_ato_normativo', 'id_ato_normativo', 'competencia', 'finalidade', 'id_usuario', 'data'];

    public function atoNormativo()
    {
        return $this->belongsTo('App\AtoNormativo', 'id_ato_normativo', 'id');
    }

    public function tipoAcaoAtoNormativo()
    {
        return $this->belongsTo('App\TipoAcaoAtoNormativo', 'id_tipo_acao_ato_normativo', 'id');
    }

    public function estruturaOrganizacional()
    {
        return $this->belongsTo('App\EstruturaOrganizacional', 'id_est_organizacional', 'id_orgao_unidade');
    }

    public function getHistorico($id_estrutura) 
    {
        return $this->where('id_est_organizacional', $id_estrutura)->with('atoNormativo', 'tipoAcaoAtoNormativo')->orderBy('id', 'desc')->get();
    }

    public function getHistoricoAto($estrutura)
    {
        $result =  $this->where('id_est_organizacional', $estrutura)->orderBy('id_ato_normativo', 'desc')->with('atoNormativo')->get();

        return $result;
    }
}
