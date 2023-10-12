<?php

namespace App\Http\Controllers;

use App\Models\Lista;
use App\Models\Nome;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class ListaController extends Controller
{
    public function lista() {
        $listas = Lista::orderBy('created_at', 'desc')->get();

        return view('dashboard.gestao.lista', ['listas' => $listas]);
    }

    public function listaDetalhe($id) {
        $lista = Lista::where('id', $id)->first();
        $query = Nome::where('id_lista', $id);

        if (Auth::user()->tipo !== 1) {
            $query->where('id_vendedor', Auth::user()->id);
        }

        $nomes = $query->get();
        $usuarios = User::all();
        $usuariosTotais = [];

        foreach ($usuarios as $usuario) {
            $cnpjs = DB::table('nome')
                ->where('id_lista', $id)
                ->where('id_vendedor', $usuario->id)
                ->where(DB::raw('CHAR_LENGTH(cpfcnpj)'), '>', 11)
                ->count();

            $cpfs = DB::table('nome')
                ->where('id_lista', $id)
                ->where('id_vendedor', $usuario->id)
                ->where(DB::raw('CHAR_LENGTH(cpfcnpj)'), '<=', 11)
                ->count();

            $totalNomes = $cpfs + $cnpjs;

            $usuariosTotais[] = [
                'nome' => $usuario->nome,
                'cpfcnpj' => $usuario->cpfcnpj,
                'totalCnpjs' => $cnpjs,
                'totalCpfs' => $cpfs,
                'totalNomes' => $totalNomes,
            ];
        }

        return view('dashboard.gestao.listaDetalhe', [
            'lista' => $lista,
            'nomes' => $nomes,
            'totalNomes' => $nomes->count(),
            'usuarios' => $usuariosTotais
        ]);
    }

    public function cadastraLista(Request $request) {

        $verificaLista = Lista::where('status', 1)->first();
        if($verificaLista && $request->status == 1) {
            return redirect()->back()->with('error', 'Já existe uma Lista aberta!');
        }

        $lista = new Lista();
        $lista->titulo = $request->titulo;
        $lista->descricao = $request->descricao;
        $lista->inicio = $request->inicio;
        $lista->fim = $request->fim;
        $lista->status = $request->status;
        $lista->save();

        return redirect()->back()->with('success', 'Lista cadastrada com sucesso!');

    }

    public function atualizaLista(Request $request) {

        $verificaLista = Lista::where('status', 1)->first();
        if($verificaLista && $request->status == 1) {
            return redirect()->back()->with('error', 'Já existe uma Lista aberta!');
        }

        $lista = Lista::where('id', $request->id)->first();
        if($lista) {
            $lista->titulo = $request->titulo;
            $lista->descricao = $request->descricao;
            $lista->inicio = $request->inicio;
            $lista->fim = $request->fim;
            $lista->status = $request->status;
            $lista->save();

            return redirect()->back()->with('success', 'Lista atualizada com sucesso!');
        }

        return redirect()->back()->with('error', 'Lista não encontrada!');

    }

    public function excluiLista(Request $request) {
        $lista = Lista::where('id', $request->id)->first();

        if($lista) {
            $lista->delete();
            return redirect()->back()->with('success', 'Lista excluída com sucesso!');
        }

        return redirect()->back()->with('error', 'Lista não encontrada!');
    }
}
