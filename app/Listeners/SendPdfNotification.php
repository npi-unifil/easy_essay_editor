<?php

namespace App\Listeners;

use App\Events\PdfGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Browsershot\Browsershot;
use App\Models\Documento;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SendPdfNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PdfGenerated  $event
     * @return void
     */
    public function handle(PdfGenerated $event)
    {
        $uid = $event->document->users_id;
        $user = User::findOrFail($uid);
        $banca = [];
        $examinador1 = '';
        foreach($event->document->banca as $item){
            array_push($banca, $item['nome']);
        }
        $template = view('template',  [
            'template' => $this,
            'curso' => $event->document->curso,
            'user' => $user->name,
            'orientador' => $event->document->orientador,
            'title' => $event->document->nome,
            'subtitulo' => '',
            'cidade' => $event->document->cidade,
            'ano' => $event->document->ano,
            'examinador1' => $banca[0],
            'examinador2' => $banca[1]
        ])->render();
        Browsershot::html('<div>'.html_entity_decode($template).'</div>')
        ->format('A4')
        ->margins(20, 20, 20, 20)
        ->footerHtml('<span class="pageNumber"></span>')
        ->initialPageNumber(9)
        ->savePdf('/home/lucas/Documentos/'.$event->document->nome.'.pdf');
    }
}
