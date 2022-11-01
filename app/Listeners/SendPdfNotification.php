<?php

namespace App\Listeners;

use App\Events\PdfGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Browsershot\Browsershot;
use App\Models\Documento;
use App\Models\User;
use DateTime;
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

    public function formatReferences($references){
        $result = [];
        $mes = array('', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        foreach($references as $reference){
            if($reference->nome_autor != null){
                $autor = explode(" ", $reference->nome_autor[0]['nome']);

            }
            if($reference->site != null){
                $acessado = explode("-", $reference->acessado);
                if(sizeof($reference->nome_autor) > 1){
                    if($reference->subtitulo == null){
                        array_push($result, <<<END
                                            <div><p><span style="text-transform: uppercase;">{$autor[count($autor)-1]}</span>, {$autor[0]}.
                                            {$reference->titulo}.
                                            {$reference->nomeDoSite}, {$reference->ano}.
                                            Disponível em: {$reference->site}.
                                            Acesso em: {$reference->acessado[2]} de {$mes[$acessado[1]]} de {$acessado[0]}.</p></div>
                                            END
                        );
                    }
                }
                if($reference->nome_autor == null){
                    if($reference->subtitulo == null){
                        array_push($result, <<<END
                                            <div><p>{$reference->nomeDoSite}. {$reference->titulo}. {$reference->nomeDoSite}, {$reference->ano}.
                                            Disponível em: {$reference->site}.
                                            Acesso em: {$reference->acessado[2]} de {$mes[$acessado[1]]} de {$acessado[0]}.</p></div>
                                            END
                        );
                    }
                }
                if(sizeof($reference->nome_autor) > 1){
                    if($reference->subtitulo != null){
                        array_push($result, <<<END
                                            <div><p><span style="text-transform: uppercase;">{$autor[count($autor)-1]}</span>, {$autor[0]}.
                                            {$reference->titulo}: {$reference->subtitulo}.
                                            {$reference->nomeDoSite}, {$reference->ano}.
                                            Disponível em: {$reference->site}.
                                            Acesso em: {$reference->acessado[2]} de {$mes[$acessado[1]]} de {$acessado[0]}.</p></div>
                                            END
                        );
                    }
                }
                if($reference->nome_autor == null){
                    if($reference->subtitulo != null){
                        array_push($result, <<<END
                                            <div><p>{$reference->nomeDoSite}. {$reference->titulo}: {$reference->subtitulo}. {$reference->nomeDoSite}, {$reference->ano}.
                                            Disponível em: {$reference->site}.
                                            Acesso em: {$reference->acessado[2]} de {$mes[$acessado[1]]} de {$acessado[0]}.</p></div>
                                            END
                        );
                    }
                }
            }else if($reference->site == null){
                if(sizeof($reference->nome_autor) >= 3){
                    $autor1 = explode(" ", $reference->nome_autor[0]['nome']);
                    $autor2 = explode(" ", $reference->nome_autor[1]['nome']);
                    $autor3 = explode(" ", $reference->nome_autor[2]['nome']);
                }
                if(sizeof($reference->nome_autor) < 4){
                    if($reference->subtitulo == null){
                        array_push($result, <<<END
                                            <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]};
                                            <span style="text-transform: uppercase;">{$autor2[count($autor)-1]}</span>, {$autor2[0]};
                                            <span style="text-transform: uppercase;">{$autor3[count($autor)-1]}</span>, {$autor3[0]}.
                                            {$reference->titulo}.
                                            {$reference->edicao}. {$reference->local}: {$reference->editora}, {$reference->ano}.</p></div>
                                            END
                        );
                    }
                    if($reference->subtitulo != null){
                        array_push($result, <<<END
                                            <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]};
                                            <span style="text-transform: uppercase;">{$autor2[count($autor)-1]}</span>, {$autor2[0]};
                                            <span style="text-transform: uppercase;">{$autor3[count($autor)-1]}</span>, {$autor3[0]}.
                                            {$reference->titulo}: {$reference->subtitulo}.
                                            {$reference->edicao}. {$reference->local}: {$reference->editora}, {$reference->ano}.</p></div>
                                            END
                        );
                    }
                }else if(sizeof($reference->nome_autor) > 3){
                    if($reference->subtitulo == null){
                        array_push($result, <<<END
                                            <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]} et al.
                                            {$reference->titulo}.
                                            {$reference->edicao}. {$reference->local}: {$reference->editora}, {$reference->ano}.</p></div>
                                            END
                        );
                    }
                    if($reference->subtitulo != null){
                        array_push($result, <<<END
                                            <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]} et al.
                                            {$reference->titulo}: {$reference->subtitulo}.
                                            {$reference->edicao}. {$reference->local}: {$reference->editora}, {$reference->ano}.</p></div>
                                            END
                        );
                    }
                }

            }

        }
        return $result;
    }

    public function handle(PdfGenerated $event)
    {
        $sortedContent = SendPdfNotification::sortEditorContent($event->document->componentes);
        $sortedContent = SendPdfNotification::getContent($sortedContent);
        $references = SendPdfNotification::formatReferences($event->document->referencias);
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
            'conteudo' => $sortedContent,
            'referencias' => $references
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
