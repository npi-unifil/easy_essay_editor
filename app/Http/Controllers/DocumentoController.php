<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Documento;
use App\Http\Controllers\ComponenteController;


class DocumentoController extends Controller
{

    public function store(Request $request){
        $nome = $request->nome;
        $users_id = $request->user()->id;
        $data = Documento::create([
            'nome'=>$nome,
            'users_id'=>$users_id
        ]);

        $componente = new ComponenteController();
        $componente->store($request, $data);
    }

}
