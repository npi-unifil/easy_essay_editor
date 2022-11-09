<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Documento;
use App\Models\User;
use App\Models\Componente;
use App\Models\Referencia;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use App\Events\PdfGenerated;
use App\Models\Capitulo;
use Spatie\Browsershot\Browsershot;

class DocumentoController extends Controller
{

// Gerenciar Trabalho Acadêmico -------------------------------------------------------
    public function novo_doc(Template $template){
        return Inertia::render('Chapters', [
            'template' => $template->id
        ]);
    }

    public function index(){
        $user = Auth::user();
        $documents = Documento::where('users_id', '=', $user->id)->get();
        $templates = Template::all();
        return Inertia::render('Documents',[
            'documents' => $documents,
            'templates' => $templates
        ]);
    }

    public function show(Documento $document){
        $document_name = $document->nome;
        $capitulos = [];
        foreach($document->capitulos as $capitulo){
            array_push($capitulos, ['id' => $capitulo->id, 'name' => $capitulo->name]);
        }

        usort($capitulos, function($obj1, $obj2){
            return $obj1['id'] > $obj2['id'];
        });

        return Inertia::render('Chapters', [
            'id' => $document->id,
            'template' => $document->templates_id,
            'document_name' => $document_name,
            'capitulos' => $capitulos,
            'orientador' => $document->orientador,
            'cidade' => $document->cidade,
            'ano' => $document->ano,
            'curso' => $document->curso,
            'banca' => $document->banca
        ]);
    }

    public function store(Request $request){
        $document = Documento::updateOrCreate(
            ['id' => $request->id],
            ['nome'=> $request->nome,
            'orientador' => $request -> orientador,
            'cidade' => $request -> cidade,
            'ano' => $request -> ano,
            'curso' => $request -> curso,
            'banca' => $request -> banca,
            'users_id'=> $request->user()->id,
            'templates_id' => $request -> template
        ]);

        return redirect()->route('documents.show', $document);
    }

    public function update(Request $request){
        $conteudo = $request->content;

        foreach ($request->removed as $id => $content){
            $componente = Componente::where('object_id', '=', $id);
            $componente->delete();
        }

        $capitulo = Capitulo::where('id', $request->id)->first();
        $document = Documento::where('id', '=', $capitulo->document_id)->first();

        if($capitulo != null){
            $capitulo->update([
                'name'=>$request->name,
                'document_id' => $capitulo->document_id
            ]);
        }

        foreach ($conteudo as $id => $item) {
            $editor = $item['editor'];
            $conteudo = $item['content'];
            $component = Componente::where('object_id', $id)->first();
            if($component != null){
                $component->update([
                    'name' => $editor['name'],
                    'conteudo' => $conteudo['value'],
                    'component_order' => $editor['component_order'],
                    'object_id' => $id,
                    'capitulos_id' => $request->id,
                ]);
            }else{
                $component = Componente::create([
                    'name' => $editor['name'],
                    'conteudo' => $conteudo['value'],
                    'component_order' => $editor['component_order'],
                    'object_id' => $id,
                    'capitulos_id' => $request->id,
                ]);
            }
        }
        return redirect()->route('documents.show', $document);
    }

    public function destroy(Documento $document){
        foreach($document->capitulos as $capitulo){
            $capitulo->componentes()->delete();
        }
        $document->capitulos()->delete();
        $document->referencias()->delete();
        $document->delete();
        return redirect()->route('documents.index');
    }

// Capitulos

    public function newChapter(Request $id){
        return Inertia::render('Dashboard', [
            'document_id' => $id->id
        ]);
    }

    public function saveChapter(Request $request){
        $capitulo = Capitulo::create([
            'name' => $request->name,
            'document_id' => $request->document_id,
        ]);
        foreach ($request->content as $id => $item) {
            $editor = $item['editor'];
            $conteudo = $item['content'];
            $capitulo->componentes()->create([
                'name' => $editor['name'],
                'conteudo' => $conteudo['value'],
                'component_order' => $editor['component_order'],
                'object_id' => $id,
                'capitulos_id' => $capitulo->id
            ]);
        }

        $document = Documento::where('id', $request->document_id)->first();

        return redirect()->route('documents.show', $document);
    }

    public function chapterComponent(Request $request){
        $capitulo = Capitulo::where('id', $request->id)->first();
        $editors = [];
        $components = $capitulo->componentes;

        foreach($components as $key){
            $editors[$key->object_id] = [
                'editor' => [
                    'name' => $key->name,
                    'component' => '',
                    'component_order' => $key->component_order
                ],
                'content' => [
                    'value' => $key->conteudo
                ]
            ];
        }

        uasort($editors, function($obj1, $obj2){
            $order1 = $obj1['editor'];
            $order2 = $obj2['editor'];
            return $order1['component_order'] > $order2['component_order'];
        });

        return Inertia::render('EditAcademicWork', [
            'chapter_id' => $capitulo->id,
            'chapter_name' => $capitulo->name,
            'edit' => $editors,
        ]);
    }

    public function removeChapter(Request $id){
        $capitulo = Capitulo::where('id', $id->id)->first();
        $document = Documento::where('id', $capitulo->document_id)->first();
        $capitulo->componentes()->delete();
        $capitulo->delete();

        return redirect()->route('documents.show', $document);
    }

    public function removeComponent(Request $id){
        $componentes = new Componente();
        $componente = $componentes->where("object_id", "=", $id['id'])->first();
        //return $componente->delete();
    }

// Gerenciar Trabalho -------------------------------------------------------
    public function gerenciar_trabalho(Documento $id){
        $document_id = $id->id;
        $document_name = $id->nome;
        $templates = Template::all();
        return Inertia::render('GerenciarTrabalho', [
            'id' => $document_id,
            'nome' => $document_name,
            'templates' => $templates
        ]);
    }

// Gerenciar Referencia -------------------------------------------------------
    public function add_referencia(Documento $id){
        return Inertia::render('Referencias/AddReferencia', [
            'titulo_da_pagina' => 'Adicionar Referência',
            'doc_id' => $id->id
        ]);
    }


    public function buscar_referencias(Documento $id){
        $referencias = Referencia::where('document_id', '=', $id->id)->get();
        return Inertia::render('Referencias/GerenciarReferencias', [
            'doc_id' => $id->id,
            'referencia' => $referencias
        ]);
    }

    public function salvar_referencia(Request $referencia){
        //dd($referencia, $referencia->nome_autor);
        $document = Referencia::updateOrCreate(
            ['id' => $referencia->id],
            ['nome_autor' => $referencia->nome_autor,
             'titulo' => $referencia->titulo,
             'subtitulo' => $referencia->subtitulo,
             'edicao' => $referencia->edicao,
             'local' => $referencia->local,
             'editora' => $referencia->editora,
             'ano' => $referencia->ano,
             'pagina' => $referencia->pagina,
             'site' => $referencia->site,
             'nomeDoSite' => $referencia->nomeDoSite,
             'acessado' => $referencia->acessado,
             'document_id' => $referencia->documento]
        );
        return redirect()->route('gerenciar_referencias', $referencia->documento);
    }

    public function editar_referencia(Referencia $id){
        return Inertia::render('Referencias/AddReferencia', [
            'titulo_da_pagina' => 'Editar Referência',
            'doc_id' => $id->document_id,
            'id' => $id->id,
            'nome_autor' => $id->nome_autor,
            'titulo' => $id->titulo,
            'subtitulo' => $id->subtitulo,
            'edicao' => $id->edicao,
            'local' => $id->local,
            'editora' => $id->editora,
            'ano' => $id->ano,
            'pagina' => $id->pagina,
            'site' => $id->site,
            'nomeDoSite' => $id->nomeDoSite,
            'acessado' => $id->acessado,
        ]);
    }

    public function deletar_referencia(Referencia $id){
        $id->delete();
        return redirect()->route('gerenciar_referencias', $id->document_id);
    }


// Formatar Trabalho Acadêmico -------------------------------------------------------
    public function exportPdf(Request $request, Documento $document){
        PdfGenerated::dispatch($document);

        return redirect()->back();
    }

    public function exportOnUpdate(Request $request){

        Browsershot::html('<div>'.html_entity_decode($request->conteudo).'</div>')
        ->format('A4')
        ->margins(20, 20, 20, 20)
        ->footerHtml('<span class="pageNumber"></span>')
        ->initialPageNumber(9)
        ->save(\storage_path().'/'.$request->nome.'.pdf');

        return redirect()->route('documents');
    }

}
