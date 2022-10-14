<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Documento;
use App\Models\Componente;
use App\Models\Referencia;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ComponenteController;
use Spatie\Browsershot\Browsershot;

class DocumentoController extends Controller
{

// Gerenciar Trabalho Acadêmico -------------------------------------------------------
    public function index(){
        $user = Auth::user();
        $documents = Documento::where('users_id', '=', $user->id)->get();
        return Inertia::render('Documents',[
            'documents' => $documents
        ]);
    }

    public function show(Documento $document){
        $editors = [];
        $document_name = $document->nome;

        $components = $document->componentes;

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
            'id' => $document->id,
            'edit' => $editors,
            'document_name' => $document_name
        ]);
    }

    public function store(Request $request){
        $content = $request -> content;
        $nome = $request -> docTitle;
        $users_id = $request->user()->id;

        $document = Documento::create([
            'nome'=>$nome,
            'users_id'=>$users_id
        ]);

        foreach ($content as $id => $item) {
            $editor = $item['editor'];
            $conteudo = $item['content'];

            $document->componentes()->create([
                'name' => $editor['name'],
                'conteudo' => $conteudo['value'],
                'component_order' => $editor['component_order'],
                'object_id' => $id,
            ]);
        }

        return redirect()->route('documents.index');
    }

    public function update(Request $request){
        $conteudo = $request->content;
        $document_id = $request->doc_id;
        $document_title = $request->docTitle;
        $removedComponent = $request->removed;

        foreach ($removedComponent as $id => $content){
            $componente = Componente::where('object_id', '=', $id);
            $componente->delete();
        }

        $document = Documento::where('id', $document_id)->first();
        if($document != null){
            $document->update(['nome'=>$document_title]);
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
                    'document_id' => $document_id,
                ]);
            }else{
                $component = Componente::create([
                    'name' => $editor['name'],
                    'conteudo' => $conteudo['value'],
                    'component_order' => $editor['component_order'],
                    'object_id' => $id,
                    'document_id' => $document_id,
                ]);
            }
        }

        return redirect()->route('documents.index');
    }

    public function destroy(Documento $document){
        $document->componentes()->delete();
        $document->referencias()->delete();
        $document->delete();
        return redirect()->route('documents.index');
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
        // dd($referencia->documento['id']);
        $document = Referencia::updateOrCreate(
            ['id' => $referencia->id],
            ['nome' => $referencia->nome,
             'sobrenome' => $referencia->sobrenome,
             'titulo' => $referencia->titulo,
             'subtitulo' => $referencia->subtitulo,
             'edicao' => $referencia->edicao,
             'local' => $referencia->local,
             'editora' => $referencia->editora,
             'ano' => $referencia->ano,
             'pagina' => $referencia->pagina,
             'site' => $referencia->site,
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
            'nome' => $id->nome,
            'sobrenome' => $id->sobrenome,
            'titulo' => $id->titulo,
            'subtitulo' => $id->subtitulo,
            'edicao' => $id->edicao,
            'local' => $id->local,
            'editora' => $id->editora,
            'ano' => $id->ano,
            'pagina' => $id->pagina,
            'site' => $id->site,
            'acessado' => $id->acessado,
        ]);
    }

    public function deletar_referencia(Referencia $id){
        $id->delete();
        return redirect()->route('gerenciar_referencias', $id->document_id);
    }

// Gerenciar Template


// Formatar Trabalho Acadêmico -------------------------------------------------------
    public function exportPdf(Request $request){
        //dd($request);
        //dd(html_entity_decode($request->value));
        Browsershot::html('<div>'.html_entity_decode($request->value).'</div>')
        ->format('A4')
        ->margins(20, 20, 20, 20)
        ->footerHtml('<span class="pageNumber"></span>')
        ->initialPageNumber(9)
        ->save(\storage_path().'/'.$request->nome.'.pdf');

        return redirect()->route('documents');
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
