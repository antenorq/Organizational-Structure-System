<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\VerificaUnidadesTemporarias;

class LembreteEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lembrete:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia lembrete por email quando estiver terminando um prazo das unidades temporárias';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $job = new VerificaUnidadesTemporarias;
        $job->verificar($this);
    }
}
