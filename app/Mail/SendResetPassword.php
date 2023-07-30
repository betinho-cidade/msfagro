<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = 'emails.reset_password.verify_link';

        return $this->from('naoresponda@mfsagro.com.br')
                //->bcc('naoresponda@aapomil.com.br')
                ->subject('MFSAGRO - Link para troca de Senha')
                ->markdown($view);
    }
}
