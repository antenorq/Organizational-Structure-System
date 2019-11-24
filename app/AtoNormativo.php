<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class AtoNormativo extends Model
{
    public $timestamps = false;
    protected $table = 'ato_normativo';
    protected $fillable = ['id_tipo_ato_normativo', 'documento', 'numero', 'data', 'data_publicacao', 'observacao', 'caput', 'id_situacao', 'introducao', 'conteudo', 'fl_tem_doc'];

    public function tipo()
    {
    	return $this->belongsTo('App\TipoAtoNormativo', 'id_tipo_ato_normativo', 'id');
    }

    public function situacao()
    {
        return $this->belongsTo('App\Situacao', 'id_situacao', 'id');
    }

    public function getAto($id)
    {
    	$result = DB::table('ato_normativo as ato')
					->select('ato.id', 'tipo.descricao as tipo', 'ato.numero', 'ato.data')
					->join('tipo_ato_normativo as tipo', 'tipo.id', '=', 'ato.id_tipo_ato_normativo')
					->where('ato.id', '=', $id)
					->get();

		return $result;
    }

    public function getAtos($input)
    {
        $result = DB::table('ato_normativo as ato')
                    ->select('ato.id', 'tipo.descricao as tipo', 'ato.numero', 'ato.data')
                    ->join('tipo_ato_normativo as tipo', 'tipo.id', '=', 'ato.id_tipo_ato_normativo')
                    ->where(DB::raw("numero"), 'LIKE', '%'.$input.'%')
                    ->orderBy('tipo.descricao')
                    ->get();

        return $result;
    }

    public function getAtosOrgao($id_orgao)
    {
        $result = DB::table('ato_normativo as ato')
                    ->select('ato.id', 'ato.numero', 'ato.caput', DB::raw('substr(ato.data, 0, 4) as ano'), 'tipo_ato.descricao as tipo_ato', 'ato.data_publicacao','ato.fl_tem_doc','ato.introducao','ato.conteudo','ato.caminho_documento')
                    ->join('tipo_ato_normativo as tipo_ato', 'tipo_ato.id', '=', 'ato.id_tipo_ato_normativo')
                    ->join('estrutura_organizacional as estrutura', 'estrutura.id_ato_normativo', '=', 'ato.id')
                    ->where('estrutura.id', '=', $id_orgao)
                    ->orWhere('estrutura.id_orgao_unidade', '=', $id_orgao)
                    //->groupBy('ato.id', 'ato.numero', 'ato.caput', 'ato.data', 'tipo_ato.descricao', 'ato.data_publicacao','ato.fl_tem_doc','ato.introducao','ato.conteudo')
                    ->orderBy('data_publicacao', 'desc')
                    ->get();

        return $result;
    }

    public function getAtoShow($id)
    {
        $result = DB::table('ato_normativo as ato')
                ->select('ato.id', 'tipo.descricao as tipo', 'ato.numero', 'ato.data', 'ato.data_publicacao', 'ato.caput', 'ato.observacao', 'ato.id_situacao', 'situacao.descricao as situacao','ato.introducao','ato.conteudo','ato.fl_tem_doc')
                ->join('tipo_ato_normativo as tipo', 'tipo.id', '=', 'ato.id_tipo_ato_normativo')
                ->join('situacao', 'situacao.id', '=', 'ato.id_situacao')
                ->where('ato.id', '=', $id)
                ->get()
                ->first();

        return $result;
    }

}
