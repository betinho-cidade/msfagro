<?php

namespace App\Mail;

use App\Models\Filiado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AcessoLiberado extends Mailable
{
    use Queueable, SerializesModels;

    public $filiado;

    public function __construct(Filiado $filiado)
    {
        $this->filiado = $filiado;
    }


    public function build()
    {
        return $this->from('naoresponda@aapomil.com.br')
                     ->subject('AAPOMIL - Cadastro Liberado')
                     ->view('emails.acessoLiberado');
    }
}
