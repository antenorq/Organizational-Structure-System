<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class EstruturaOrganizacional extends Model
{
	public $timestamps = false;
    protected $table = 'estrutura_organizacional';
	protected $fillable = [
		'id_ato_normativo',
		'id_sit_estr_organizacional',
		'id_endereco',
		'id_tipo_hierarquia',
		'id_funcao',
		'descricao',
		'sigla',
		'email',
		'telefone',
		'obs_ato_normativo',
		'id_tipo_acao_ato_normativo',
		'cnpj',
		'site',
		'horario_funcionamento',
		'id_unidade_representacao',
		'id_orgao_orgaocolegiado',
		'id_orgao_unidade',
		'id_orgao_vinculacao',
		'id_unidade_subordinacao',
		'data_fim',
		'competencia',
		'finalidade',
	];

	public function funcao()
	{
		return $this->belongsTo('App\Funcao','id_funcao','id');
	}

	public function situacao()
	{
		return $this->belongsTo('App\Situacao','id_sit_estr_organizacional','id');
	}

	public function atoNormativo()
	{
		return $this->belongsTo('App\AtoNormativo', 'id_ato_normativo', 'id');
	}

	public function tipoAcaoAtoNormativo()
	{
		return $this->belongsTo('App\TipoAcaoAtoNormativo','id_tipo_acao_ato_normativo','id');
	}

	public function tipoHierarquia()
	{
		return $this->belongsTo('App\TipoHierarquia','id_tipo_hierarquia','id');
	}

	public function orgaoColegiadoRel()
	{
		return $this->belongsTo('App\EstruturaOrganizacional','id_orgao_orgaocolegiado','id');
	}

	public function orgaoUnidade()
	{
		return $this->belongsTo('App\EstruturaOrganizacional','id_orgao_unidade','id');
	}

	public function cargosUnidade()
	{
		return $this->hasMany('App\CargoUnidade','id_unidade');
	}

	public function getUnidadesRepresentacao()
	{		
		$unidades = $this->select(DB::raw('(sigla|| \' - \'|| descricao) descricao '),'id')->where('id_tipo_estrutura',2)->whereIn('id_sit_estr_organizacional', [1,5])->where('id_tipo_hierarquia',9)->orderBy('sigla')->pluck('descricao', 'id');
		return $unidades;
	}

	public function getUnidades($orgao = NULL)
	{
		//carrega unidades do órgão
		if($orgao)
		{
			$unidades = $this->select(DB::raw('(sigla|| \' - \'|| descricao) descricao '),'id')->where('id_tipo_estrutura',2)->where('id_orgao_unidade',$orgao)->whereIn('id_sit_estr_organizacional', [1,5])->orderBy('sigla')->pluck('descricao', 'id');
			return $unidades;
		}
		//carrega todas as unidades
		else
		{
			$unidades = $this->select(DB::raw('(sigla|| \' - \'|| descricao) descricao '),'id')->where('id_tipo_estrutura',2)->whereIn('id_sit_estr_organizacional', [1,5])->orderBy('sigla')->pluck('descricao', 'id');
			return $unidades;
		}
		
	}

	public function getOrgaos()
	{
		$orgaos = $this->select(DB::raw('(sigla|| \' - \'|| descricao) descricao '),'id')->where('id_tipo_estrutura',1)->orderBy('sigla')->pluck('descricao', 'id');
		
		return $orgaos;
	}

	public function getConselhos()
	{
		$orgaos = $this->select(DB::raw('(sigla|| \' - \'|| descricao) descricao '),'id')->where('id_tipo_estrutura',3)->whereIn('id_sit_estr_organizacional', [1,5])->orderBy('sigla')->pluck('descricao', 'id');
		return $orgaos;
	}

	public function getHierarquiaOrgao($orgao)
	{
		return $this->where('id_orgao_unidade', $orgao)->where('id_tipo_estrutura', 2)->whereIn('id_sit_estr_organizacional', [1,5])->orderBy('sigla')->get();
	}

	public function getOrgaosVinculados($orgao)
	{
		return $this->where('id_orgao_vinculacao', $orgao)->where('id_tipo_estrutura', 1)->whereIn('id_sit_estr_organizacional', [1,5])->orderBy('sigla')->get();
	}

	public function getOrgaosColegiados($orgao)
	{
		return $this->where('id_orgao_orgaocolegiado', $orgao)->where('id_tipo_estrutura', 3)->whereIn('id_sit_estr_organizacional', [1,5])->orderBy('sigla')->get();
	}

	public function getSigla($id_estrutura)
	{
		$result = $this->where('id', $id_estrutura)->get()->first();
		return $result->sigla;
	}

	public function getDescricao($id_estrutura)
	{
		$result = $this->where('id', $id_estrutura)->get()->first();
		return $result->descricao;
	}

	public function getShowOrgao($id)
	{
		$result = DB::table('estrutura_organizacional as orgao')
					->select('orgao.id', 'orgao.descricao', 'orgao.sigla', 'orgao.competencia', 'orgao.finalidade', 'orgao.telefone', 'orgao.email', 'orgao.cnpj', 'orgao.site', 'orgao.horario_funcionamento', 'orgao.competencia', 'orgao.finalidade', 'orgao.horario_funcionamento', 'orgao.id_sit_estr_organizacional', 'orgao.id_orgao_vinculacao', 'orgao.id_unidade_representacao', 'orgao.data_fim', 'tipo_estrutura_organizacional.descricao as tipo_estrutura_descricao', 'situacao.descricao as situacao_descricao', 'orgao.obs_ato_normativo','tipo_hierarquia.descricao as tipo_hierarquia_descricao', 'funcao.descricao as funcao_descricao', 'endereco.bairro', 'endereco.logradouro', 'endereco.complemento', 'endereco.cep', 'endereco.numero as numero_endereco', 'tipo_ato.descricao as tipo_ato_descricao', 'ato.numero as numero_ato', 'ato.data', 'orgao.obs_ato_normativo')
					->join('tipo_estrutura_organizacional', 'tipo_estrutura_organizacional.id', '=', 'orgao.id_tipo_estrutura')
					->join('tipo_hierarquia', 'tipo_hierarquia.id', '=', 'orgao.id_tipo_hierarquia')
					->leftjoin('funcao', 'funcao.id', '=', 'orgao.id_funcao')
					->join('situacao', 'situacao.id', '=', 'orgao.id_sit_estr_organizacional')
					->leftjoin('endereco', 'endereco.id', '=', 'orgao.id_endereco')
					->leftjoin('ato_normativo as ato', 'ato.id', '=', 'orgao.id_ato_normativo')
					->leftjoin('tipo_ato_normativo as tipo_ato', 'tipo_ato.id', '=', 'ato.id_tipo_ato_normativo')
					->where('orgao.id', '=', $id)
					->get()
					->first();

		return $result;
	}

	public function getShowUnidade($id)
	{
		$result = DB::table('estrutura_organizacional as unidade')
					->select('unidade.id', 'unidade.descricao', 'unidade.sigla', 'unidade.competencia', 'unidade.finalidade', 'unidade.telefone', 'unidade.email', 'unidade.id_orgao_unidade', 'unidade.id_unidade_subordinacao', 'unidade.id_sit_estr_organizacional', 'unidade.data_fim', 'tipo_hierarquia.descricao as tipo_hierarquia_descricao', 'situacao.descricao as situacao_descricao', 'tipo_ato.descricao as tipo_ato_descricao', 'ato.numero', 'ato.data', 'ato.observacao')
					->join('tipo_hierarquia', 'tipo_hierarquia.id', '=', 'unidade.id_tipo_hierarquia')
					->join('situacao', 'situacao.id', '=', 'unidade.id_sit_estr_organizacional')
					->join('ato_normativo as ato', 'ato.id', '=', 'unidade.id_ato_normativo')
					->join('tipo_ato_normativo as tipo_ato', 'tipo_ato.id', '=', 'ato.id_tipo_ato_normativo')
					->where('unidade.id', '=', $id)
					->get()
					->first();

		return $result;
	}

	public function getShowOrgaoColegiado($id)
	{
		$result = DB::table('estrutura_organizacional as orgao_colegiado')
					->select('orgao_colegiado.id', 'orgao_colegiado.descricao', 'orgao_colegiado.sigla', 'orgao_colegiado.competencia', 'orgao_colegiado.finalidade', 'orgao_colegiado.id_sit_estr_organizacional', 'orgao_colegiado.id_orgao_orgaocolegiado', 'orgao_colegiado.data_fim', 'tipo_hierarquia.descricao as tipo_hierarquia_descricao', 'funcao.descricao as funcao_descricao', 'situacao.descricao as situacao_descricao', 'tipo_ato.descricao as tipo_ato_descricao', 'ato.numero', 'ato.data', 'ato.observacao')
					->join('funcao', 'funcao.id', '=', 'orgao_colegiado.id_funcao')
					->join('tipo_hierarquia', 'tipo_hierarquia.id', '=', 'orgao_colegiado.id_tipo_hierarquia')
					->join('situacao', 'situacao.id', '=', 'orgao_colegiado.id_sit_estr_organizacional')
					->join('ato_normativo as ato', 'ato.id', '=', 'orgao_colegiado.id_ato_normativo')
					->join('tipo_ato_normativo as tipo_ato', 'tipo_ato.id', '=', 'ato.id_tipo_ato_normativo')
					->where('orgao_colegiado.id', '=', $id)
					->get()
					->first();

		return $result;
	}

	public function getHierarquia($id)
	{
		$result = DB::table('estrutura_organizacional')
					->select('sigla', 'descricao')
					->where('id', '=', $id)
					->get()
					->first();

		return $result;
	}
}
