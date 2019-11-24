<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AvisoUnidadeTemporaria extends Mailable
{
    use Queueable, SerializesModels;

    protected $dadosEmail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dados)
    {
        $this->dadosEmail = $dados;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->dadosEmail['view'])
                    ->with($this->dadosEmail['vars'])
                    ->subject($this->dadosEmail['assunto']);
    }
}
