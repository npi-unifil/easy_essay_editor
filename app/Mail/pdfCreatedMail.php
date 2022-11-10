<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class pdfCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $user;
    private $path;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, String $path)
    {
        $this->user = $user;
        $this->path = $path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("Novo pdf gerado!");
        $this->to($this->user->email, $this->user->name);
        $this->view('mail.pdfCreatedMail', [
            'user' => $this->user,
            'link' => $this->path
        ]);
    }
}
