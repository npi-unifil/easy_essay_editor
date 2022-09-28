<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Documento;
use App\Models\Componente;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ComponenteController;
use Spatie\Browsershot\Browsershot;

class DocumentoController extends Controller
{

    public function index(){
        $user = Auth::user();
        $documents = Documento::where('users_id', '=', $user->id)->get();
        return Inertia::render('Documents',[
            'documents' => $documents
        ]);
    }

    public function gerenciar_trabalho(Documento $id){
        $document_id = $id->id;
        $document_name = $id->nome;

        return Inertia::render('GerenciarTrabalho', [
            'id' => $document_id,
            'nome' => $document_name
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
        //dd($request);
        $document_id = $request->id;
        $documents = Documento::where('document_id', '=', $document_id)->first();
        $documents->update($request->all('nome'));

        $componente = new ComponenteController();
        $componente->update($request);

        return redirect()->route('documents');
    }

    public function destroy(Documento $document){
        $document->componentes()->delete();
        $document->delete();
        return redirect()->route('documents.index');
    }

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
