<?php

namespace App\Listeners;

use App\Events\PdfGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Browsershot\Browsershot;

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
        $content = view('template', ['template' => $this])->render();
        Browsershot::html('<div>'.html_entity_decode($content).'</div>')
        ->format('A4')
        ->margins(20, 20, 20, 20)
        ->footerHtml('<span class="pageNumber"></span>')
        ->initialPageNumber(9)
        ->savePdf('/home/lucas/Documentos/'.$event->document->nome.'.pdf');
    }
}
