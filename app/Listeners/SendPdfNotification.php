<?php

namespace App\Listeners;

use App\Events\PdfGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Browsershot\Browsershot;
use App\Models\Documento;
use App\Models\User;
use App\Mail\pdfCreatedMail;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
    public function sliceChapters($chapters){
        $result = [];
        $index = 0;
        foreach($chapters as $chapter){
            foreach($chapter->componentes as $componente){
                $result[$chapter->name][$index] = [];
                array_push($result[$chapter->name][$index], [
                        'name' => $componente->name,
                        'component_order' => $componente->component_order,
                        'value' => $componente->conteudo
                        ]
                    );
                uasort($result[$chapter->name], function($obj1, $obj2){
                    $order1 = 0;
                    $order2 = 0;
                    foreach($obj1 as $ob1){
                        $order1 = $ob1['component_order'];
                    }
                    foreach($obj2 as $ob2){
                        $order2 = $ob2['component_order'];
                    }
                    return $order1 > $order2;
                });
                $index++;
            }
            $index = 0;
        }
        return $result;
    }

    public function setContent($conteudo){
        $result = [];
        foreach($conteudo as $item){
            foreach($item as $html){
                array_push($result, $html['value']);
            }
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
                $acessado[1] = str_replace('0', '', $acessado[1]);
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
                if(sizeof($reference->nome_autor) == 1){
                    $autor1 = explode(" ", $reference->nome_autor[0]['nome']);
                    if($reference->subtitulo == null){
                        if($reference->edicao == null){
                            if($reference->local == null){
                                array_push($result, <<<END
                                                    <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]}.
                                                    {$reference->titulo}.{$reference->editora}, {$reference->ano}.</p></div>
                                                    END
                                );
                            }else{
                                array_push($result, <<<END
                                                    <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]}.
                                                    {$reference->titulo}.{$reference->local}: {$reference->editora}, {$reference->ano}.</p></div>
                                                    END
                                );
                            }
                        }
                    }
                    if($reference->subtitulo != null){
                        if($reference->edicao == null){
                            if($reference->local == null){
                                array_push($result, <<<END
                                                    <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]}.
                                                    {$reference->titulo}.{$reference->editora}, {$reference->ano}.</p></div>
                                                    END
                                );
                            }else{
                                array_push($result, <<<END
                                                    <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]}.
                                                    {$reference->titulo}: {$reference->subtitulo}.{$reference->local}: {$reference->editora}, {$reference->ano}.</p></div>
                                                    END
                                );
                            }
                        }
                    }

                }
                if(sizeof($reference->nome_autor) >= 3){
                    $autor1 = explode(" ", $reference->nome_autor[0]['nome']);
                    $autor2 = explode(" ", $reference->nome_autor[1]['nome']);
                    $autor3 = explode(" ", $reference->nome_autor[2]['nome']);
                }
                if(sizeof($reference->nome_autor) == 3){
                    if($reference->subtitulo == null){
                        if($reference->edicao == null){
                            if($reference->local == null){
                                array_push($result, <<<END
                                <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]};
                                <span style="text-transform: uppercase;">{$autor2[count($autor)-1]}</span>, {$autor2[0]};
                                <span style="text-transform: uppercase;">{$autor3[count($autor)-1]}</span>, {$autor3[0]}.
                                {$reference->titulo}.{$reference->editora}, {$reference->ano}.</p></div>
                                END
                                );
                            }else{
                                array_push($result, <<<END
                                <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]};
                                <span style="text-transform: uppercase;">{$autor2[count($autor)-1]}</span>, {$autor2[0]};
                                <span style="text-transform: uppercase;">{$autor3[count($autor)-1]}</span>, {$autor3[0]}.
                                {$reference->titulo}.{$reference->local}: {$reference->editora}, {$reference->ano}.</p></div>
                                END
                                );
                            }
                        }else{
                            if($reference->local == null){
                                array_push($result, <<<END
                                <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]};
                                <span style="text-transform: uppercase;">{$autor2[count($autor)-1]}</span>, {$autor2[0]};
                                <span style="text-transform: uppercase;">{$autor3[count($autor)-1]}</span>, {$autor3[0]}.
                                {$reference->titulo}.{$reference->editora}, {$reference->ano}.</p></div>
                                END
                                );
                            }else{
                                array_push($result, <<<END
                                                    <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]};
                                                    <span style="text-transform: uppercase;">{$autor2[count($autor)-1]}</span>, {$autor2[0]};
                                                    <span style="text-transform: uppercase;">{$autor3[count($autor)-1]}</span>, {$autor3[0]}.
                                                    {$reference->titulo}.
                                                    {$reference->edicao}. {$reference->local}: {$reference->editora}, {$reference->ano}.</p></div>
                                                    END
                                );
                            }
                        }
                    }
                    if($reference->subtitulo != null){
                        if($reference->edicao == null){
                            array_push($result, <<<END
                                                <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]}.
                                                {$reference->titulo}: {$reference->subtitulo}.{$reference->local}: {$reference->editora}, {$reference->ano}.</p></div>
                                                END
                            );
                        }else{
                            array_push($result, <<<END
                                                <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]};
                                                <span style="text-transform: uppercase;">{$autor2[count($autor)-1]}</span>, {$autor2[0]};
                                                <span style="text-transform: uppercase;">{$autor3[count($autor)-1]}</span>, {$autor3[0]}.
                                                {$reference->titulo}: {$reference->subtitulo}.
                                                {$reference->edicao}. {$reference->local}: {$reference->editora}, {$reference->ano}.</p></div>
                                                END
                            );
                        }
                    }
                }else if(sizeof($reference->nome_autor) > 3){
                    if($reference->subtitulo == null){
                        if($reference->edicao == null){
                            if($reference->local == null){
                                array_push($result, <<<END
                                                <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]} et al.
                                                {$reference->titulo}.{$reference->editora}, {$reference->ano}.</p></div>
                                                END
                                );
                            }else{
                                array_push($result, <<<END
                                                    <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]} et al.
                                                    {$reference->titulo}.{$reference->local}: {$reference->editora}, {$reference->ano}.</p></div>
                                                    END
                                );
                            }
                        }else{
                            if($reference->local == null){
                                array_push($result, <<<END
                                                <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]} et al.
                                                {$reference->titulo}.{$reference->editora}, {$reference->ano}.</p></div>
                                                END
                                );
                            }else{
                                array_push($result, <<<END
                                                    <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]} et al.
                                                    {$reference->titulo}.
                                                    {$reference->edicao}. {$reference->local}: {$reference->editora}, {$reference->ano}.</p></div>
                                                    END
                                );
                            }
                        }
                    }
                    if($reference->subtitulo != null){
                        if($reference->edicao == null){
                            array_push($result, <<<END
                                                <div><p><span style="text-transform: uppercase;">{$autor1[count($autor)-1]}</span>, {$autor1[0]}.
                                                {$reference->titulo}: {$reference->subtitulo}.{$reference->local}: {$reference->editora}, {$reference->ano}.</p></div>
                                                END
                            );
                        }else{
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

        }
        return $result;
    }

    public function handle(PdfGenerated $event)
    {
        $capitulosSeparados = SendPdfNotification::sliceChapters($event->document->capitulos);
        $dedicatoria = [];
        $agradecimentos = [];
        $epigrafe = [];
        $introducao = [];
        $resumo = [];
        $listaAbreviatura = [];
        $desenvolvimento = [];
        foreach($capitulosSeparados as $capitulo => $value){
            foreach($value as $lista){
                foreach($lista as $html){
                    if($html['name'] === 'listaAbreviatura'){
                        array_push($listaAbreviatura, $html['value']);
                    }
                }
            }
            if(strcasecmp($capitulo, 'dedicatória') == 0 | strcasecmp($capitulo, 'dedicatoria') == 0){
                $dedicatoria = SendPdfNotification::setContent($value);
            }
            if(strcasecmp($capitulo, 'agradecimentos') == 0){
                $agradecimentos = SendPdfNotification::setContent($value);
            }
            if(strcasecmp($capitulo, 'epigrafe') == 0 | strcasecmp($capitulo, 'epígrafe') == 0){
                $epigrafe = SendPdfNotification::setContent($value);
            }
            if(strcasecmp($capitulo, 'introdução') == 0){
                $introducao = SendPdfNotification::setContent($value);
            }
            if(strcasecmp($capitulo, 'resumo') == 0){
                $resumo = SendPdfNotification::setContent($value);
            }if(strcasecmp($capitulo, 'resumo') != 0 && strcasecmp($capitulo, 'introdução') != 0
                && strcasecmp($capitulo, 'dedicatória') != 0 && strcasecmp($capitulo, 'agradecimentos') != 0
                && strcasecmp($capitulo, 'epigrafe') != 0){
                $desenvolvimento[$capitulo] = SendPdfNotification::setContent($value);
            }
        };
        array_splice($desenvolvimento, 0, 1);

        if($event->document->dedicatoria == 'false'){
            $dedicatoria = [];
        }
        if($event->document->agradecimentos == 'false'){
            $agradecimentos = [];
        }
        if($event->document->epigrafe == 'false'){
            $epigrafe = [];
        }

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
            'resumo' => $resumo,
            'dedicatoria' => $dedicatoria,
            'agradecimentos' => $agradecimentos,
            'epigrafe' => $epigrafe,
            'introducao' => $introducao,
            'listaAbreviaturas' => $listaAbreviatura,
            'desenvolvimento' => $desenvolvimento,
            'referencias' => $references
        ])->render();
        // $pdf_created = Browsershot::html('<div>'.html_entity_decode($template).'</div>')
        Browsershot::html('<div>'.html_entity_decode($template).'</div>')
        ->format('A4')
        ->margins(20, 20, 20, 20)
        ->showBrowserHeaderAndFooter()
        ->headerHtml('<span class="pageNumber"></span>')
        ->initialPageNumber(8)
        ->savePdf('/home/lucas/Documentos/'.$event->document->nome.'.pdf');
        // ->base64pdf();
        // $nomeDoArquivo = Str::slug($event->document->nome, '-').'.pdf';
        // Storage::disk('s3')->put($nomeDoArquivo, base64_decode($pdf_created));
        // $path = Storage::disk('s3')->url($nomeDoArquivo);
        // Mail::send(new pdfCreatedMail($user, $path));

    }
}
