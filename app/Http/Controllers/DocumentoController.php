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
        $documents = Documento::all('nome');
        return Inertia::render('Documents',[
            'documents' => $documents
        ]);
    }

    public function store(Request $request){
        $nome = $request;
        $users_id = $request->user()->id;
        $data = Documento::create([
            'nome'=>$nome,
            'users_id'=>$users_id
        ]);

        $componente = new ComponenteController();
        $componente->store($request, $data);

        return redirect()->route('documents');
    }



}
