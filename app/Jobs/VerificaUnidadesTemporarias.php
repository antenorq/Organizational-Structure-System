<?php

namespace App\Jobs;

use Illuminate\Console\Command;
use App\EstruturaOrganizacional;
use DB;
use Carbon\Carbon;

use App\Mail\AvisoUnidadeTemporaria;
use Illuminate\Support\Facades\Mail;
use App\Http\Helpers;

class VerificaUnidadesTemporarias
{

    public $helper;

    public function __construct()
    {
        $this->helper = new helpers;
    }

    public function verificar(Command $c)
    {

        $c->info("Buscando Unidades Temporárias...");

        DB::beginTransaction();
        try
        {
            $unidades = EstruturaOrganizacional::select('descricao', 'sigla','data_fim')->where('id_tipo_estrutura',2)->get();

            $c->info("Busca unidades\n\n");
            
            foreach ($unidades as $unidade)
            {
                // Verifica se a data não é nula e se unidade expira amanhã
                if ($unidade->data_fim && $this->expiraAmanha($unidade->data_fim))
                {                    
                    $this->enviarEmail($unidade->descricao, $unidade->sigla, $unidade->data_fim, "unidade",$c);
                    $c->info("Unidade: ". $unidade->sigla . " irá expirar");
                }
            }
        } 
        catch(\Exception $e)
        {
            \Log::error($e->getMessage());
            $c->error($e->getMessage());
            DB::rollback();
        }
    }   


    /*
    *   Verifica se um determinado orgão/unidade irá expirar amanhã
    *   @return boolean
    */
    private function expiraAmanha($data_fim) {
        $data_fim = Carbon::parse($data_fim);    // Data fim da unidade
        $data_atual = Carbon::today();           // Data atual

        $daysDiff = $data_atual->diffInDays($data_fim); // Diferença de dias entre as datas

        if ($daysDiff == 1)
            return true;
        else return false;
    }

    /*
    *   Cria e envia um email relacionado à expiração de unidade.
    */
    public function enviarEmail($descricao, $sigla, $data, $tipo, Command $c) {

        $dadosEmail = [];        
        $dadosEmail['assunto'] = "Unidade irá expirar";
        $dadosEmail['view'] = "email.unidade_temporaria";
        

        $c->info("Enviando email...\n\n");

        $dadosEmail['vars'] = [
            'sigla' => $sigla,
            'descricao' => $descricao,
            'data' => $this->helper->date_system_format_oracle($data)
        ];

        Mail::to('antenor.queiroz@salvador.ba.gov.br')->send(
            new AvisoUnidadeTemporaria($dadosEmail)
        );
    }
}