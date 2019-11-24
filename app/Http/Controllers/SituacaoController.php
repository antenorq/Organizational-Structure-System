<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EstruturaOrganizacional;
use App\Cargo;
use App\AtoNormativo;
use App\Services\HistoricoService;

class SituacaoController extends Controller
{
    public $estrutura_organizacional;
    public $cargo;
    public $ato;

    public function __construct()
    {
        $this->estrutura_organizacional = new EstruturaOrganizacional;
        $this->cargo = new Cargo;
        $this->ato = new AtoNormativo;
    }

    public function update(Request $request)
    {       
        switch($request->tipo)
        {
            case 'estrutura':
                $model = $this->estrutura_organizacional->find($request->estrutura);
                $model->id_sit_estr_organizacional = $request->situacao;
                $historico = new HistoricoService();
                $historico->salvarDados($model);
            break;

            case 'cargo':
                $model = $this->cargo->find($request->cargo);
                $model->id_situacao = $request->situacao;
            break;

            case 'ato':
                $model = $this->ato->find($request->ato);
                $model->id_situacao = $request->situacao;
            break;
        }
        
        if($model->save())
            echo json_encode(true);
        else
            echo json_encode(false);
    }
}
