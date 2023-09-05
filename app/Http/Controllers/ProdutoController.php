<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function limpaNome($id) {
        return view('franquias.limpanome', ['id' => $id]);
    }

    public function kannanda($id) {
        return view('franquias.kannanda', ['id' => $id]);
    }
}
