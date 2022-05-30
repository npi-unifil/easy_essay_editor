<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Documento;


class DocumentoController extends Controller
{

    public function store(Request $request){
        $nome = $request->nome;
        $users_id = $request->user()->id;
        Documento::create([
            'nome'=>$nome,
            'users_id'=>$users_id
        ]);
    }

}
