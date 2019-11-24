<?php

namespace App\Repositories;

use App\Gestor;
use App\Equipe;

class EquipeRepository
{
    public function getPorOrgao($id_orgao)
    {
        $result = Equipe::select(
                            'equipe.id as id', 'equipe.nome', 'cargo.id as id_cargo', 'cargo.descricao as cargo',
                            'estrutura.id as id_unidade', 'estrutura.descricao as unidade')
                            ->join('cargo', 'cargo.id', '=', 'equipe.id_cargo')
                            ->join('estrutura_organizacional as estrutura', 'estrutura.id', '=', 'equipe.id_unidade')
                            ->where('estrutura.id_orgao_unidade', '=', $id_orgao)
                            ->orderBy('estrutura.descricao')
                            ->get();

    	return $result;
    }
}
