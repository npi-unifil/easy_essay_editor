<?php

namespace App\Listeners;

use App\Events\PdfGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Browsershot\Browsershot;
use App\Models\Documento;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SendPdfNotification
implements ShouldQueue
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
    public function formatContent($conteudo){
        $result = [];
        foreach($conteudo as $item){
            $value = str_replace(['class="ql-align-center"', 'class="ql-size-huge"'], ['style="text-align:center; font-size: h1"', ''], $item);
            array_push($result, $value);
        }
        return $result;
    }

    public function sortEditorContent($conteudo){
        $editors = [];
        foreach($conteudo as $key){
            $editors[$key->object_id] = [
                'content' => [
                    'name' => $key->name,
                    'component_order' => $key->component_order,
                    'value' => $key->conteudo
                ]
            ];
        }
        uasort($editors, function($obj1, $obj2){
            $order1 = $obj1['content'];
            $order2 = $obj2['content'];
            return $order1['component_order'] > $order2['component_order'];
        });
        return $editors;
    }

    public function getContent($editor){
        $result = [];
        foreach($editor as $key){
            array_push($result, $key['content']['value']);
        }
        return $result;
    }

    public function handle(PdfGenerated $event)
    {
        $conteudoDoEditor = [];
        $sortedContent = SendPdfNotification::sortEditorContent($event->document->componentes);
        $sortedContent = SendPdfNotification::getContent($sortedContent);
        // for($i = 0; $i < count($event->document->componentes); $i++){
        //     array_push($conteudoDoEditor, $event->document->componentes[$i]->conteudo);
        // };

        //$conteudoFormatado = SendPdfNotification::formatContent($sortedContent);
        $uid = $event->document->users_id;
        $user = User::findOrFail($uid);
        $banca = [];
        foreach($event->document->banca as $item){
            array_push($banca, $item['nome']);
        };
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
            'examinador2' => $banca[1],
            'conteudo' => $sortedContent
        ])->render();
        //$pdf_created = Browsershot::html('<div>'.html_entity_decode($template).'</div>')
        Browsershot::html('<div>'.html_entity_decode($template).'</div>')
        ->format('A4')
        ->margins(20, 20, 20, 20)
        ->footerHtml('<span class="pageNumber"></span>')
        ->initialPageNumber(9)
        ->savePdf('/home/lucas/Documentos/'.$event->document->nome.'.pdf');
        //->base64pdf();
        //$nomeDoArquivo = Str::slug($event->document->nome, '-');
        //Storage::disk('s3')->put($nomeDoArquivo.'.pdf', base64_decode($pdf_created));


    }
}
