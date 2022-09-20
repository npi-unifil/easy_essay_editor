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

    public function getById($id){
        $user = Auth::user()->id;
        $documents = Documento::findOrFail($id);
        $components = Componente::where('document_id', '=', $id)->get();

        $document_name = $documents->nome;
        foreach ($components as $key){
            $editors[$key->object_id] = [
                'editor' => [
                    'name' => $key->name,
                    'component' => '',
                    'component_order' => $key->component_order
                ],
                'content' => [
                    'value' => $key->conteudo
                ]];
        }
        //dd($editors);

        if($user == $documents->users_id){
            return Inertia::render('EditAcademicWork', [
                'edit' => $editors,
                'document_name' => $document_name
            ]);
        }
        return Inertia::render('NotFound');
    }

    public function store(Request $request){
        $content = $request -> content;
        $components = [];
        $componentName = '';
        $contentsInside = [];
        $contents = '';
        $count = 0;

        $nome = $request -> docTitle;
        $users_id = $request->user()->id;

        $data = Documento::create([
            'nome'=>$nome,
            'users_id'=>$users_id
        ]);
        $componente = new ComponenteController();

        foreach ($content as $id => $key) {
            $components[$count] = $key['editor'];
            $componentName = $components[$count]['name'];
            $component_order = $components[$count]['component_order'];
            $object_id = $id;
            $contentsInside[$count] = $key['content']['value'];
            $contents = $contentsInside[$count];

            $componente->store($componentName, $component_order, $object_id, $contents, $data);

            $count++;
        }


        return redirect()->route('documents');
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

    public function destroy(Request $request, $id){
        $componente = new ComponenteController();
        $componente->destroy($id);

        $documents = Documento::where('document_id', '=', $id)->first();
        $documents->delete('DELETE FROM documents WHERE id = ?', [$id]);

        return redirect()->route('documents');
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
