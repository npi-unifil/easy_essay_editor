<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Documento;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ComponenteController;
use Spatie\Browsershot\Browsershot;

class DocumentoController extends Controller
{

    public function index(){
        $user = Auth::user()->id;
        $documents = Documento::where('users_id', '=', $user)->get();
        return Inertia::render('Documents',[
            'documents' => $documents
        ]);
    }

    public function getById($id){
        $user = Auth::user()->id;

        $documents = Documento::findOrFail($id)->join('componentes', 'componentes.document_id', '=', 'documents.document_id')->findOrFail($id);

        if($user == $documents->users_id){
            return Inertia::render('EditAcademicWork', [
                'edit' => $documents
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

        foreach ($content as $key) {
            $components[$count] = $key['editor'];
            $components[$count] = $components[$count]['name'];
            $componentName = $components[$count];
            $contentsInside[$count] = $key['content']['value'];
            $contents = $contentsInside[$count];

            $componente->store($componentName, $contents, $data);

            $count++;
        }

        //dd($title, $content);


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
