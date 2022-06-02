<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Documento;
use App\Http\Controllers\ComponenteController;

class DocumentoController extends Controller
{

    public function index(){
        $documents = Documento::all('document_id', 'nome');
        return Inertia::render('Documents',[
            'documents' => $documents
        ]);
    }

    public function getById($id){
        $documents = Documento::findOrFail($id)->join('componentes', 'componentes.document_id', '=', 'documents.document_id')->findOrFail($id);
        //dd($documents);
        return Inertia::render('EditAcademicWork', [
            'edit' => $documents
        ]);
    }

    public function store(Request $request){
        $nome = $request->nome;
        $users_id = $request->user()->id;
        $data = Documento::create([
            'nome'=>$nome,
            'users_id'=>$users_id
        ]);

        $componente = new ComponenteController();
        $componente->store($request, $data);

        return redirect()->route('documents');
    }

    public function update(Request $request, $id){
        //dd($request->id);
        $document_id = $request->id;
        $documents = Documento::where('document_id', '=', $document_id)->first();
        $documents->update($request->all('nome'));

        $componente = new ComponenteController();
        $componente->update($request);

        return redirect()->route('documents');
    }

}
