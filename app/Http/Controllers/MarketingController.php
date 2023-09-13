<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


use App\Models\Marketing;
use App\Models\Notificacao;

class MarketingController extends Controller
{
    public function marketing($id) {

        $materiais = Marketing::where('id_produto', $id)->get();
        return view('dashboard.vendas.marketing', [
            'materiais' => $materiais,
        ]);

    }

    public function materiais() {

        $materiais = Marketing::all();
        return view('dashboard.mkt.materiais', [
            'materiais' => $materiais,
        ]);

    }

    public function action_materiais(Request $request) {

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'descricao' => 'required',
            'id_produto' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $arquivoUrl = null;

        if ($request->hasFile('arquivo')) {
            $validator = Validator::make($request->all(), [
                'arquivo' => 'file|max:2048', // Exemplo: tamanho máximo de 2MB
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $arquivo = $request->file('arquivo');
            $arquivoNome = time() . '_' . $arquivo->getClientOriginalName();
            $arquivo->storeAs('public/marketing', $arquivoNome);
            $arquivoUrl = Storage::url('marketing/' . $arquivoNome);
        }

        $user = auth()->user();

        Marketing::create([
            'id_produto' => $request->input('id_produto'),
            'id_user' => $user->id,
            'nome' => $request->input('nome'),
            'descricao' => $request->input('descricao'),
            'arquivo' => $arquivoUrl ?? $request->input('url'),
            'tipo' => $request->input('tipo'),
        ]);

        return redirect()->back()->with('success', 'Sucesso! Material cadastrado!');
    }

    public function materiais_delete(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $id = $request->input('id');
        $material = Marketing::find($id);

        if ($material) {
            $material->delete();

            return redirect()->back()->with('success', 'Sucesso! Material excluido!');
        } else {
            return redirect()->back()->with('error', 'Material não encontrado.');
        }
    }

    public function notificacoes() {

        $notificacoes = Notificacao::all();
        return view('dashboard.mkt.notificacoes', [
            'notificacoes' => $notificacoes,
        ]);

    }

    public function action_notificacoes(Request $request) {

        $validator = Validator::make($request->all(), [
            'titulo' => 'required',
            'mensagem' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = auth()->user();

        Notificacao::create([
            'titulo'    => $request->input('titulo'),
            'mensagem'  => $request->input('mensagem'),
            'id_user'   => $user->id,
        ]);

        return redirect()->back()->with('success', 'Sucesso! Mensagem cadastrada!');
    }

    public function notificacoes_delete(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $id = $request->input('id');
        $notificacao = Notificacao::find($id);

        if ($notificacao) {
            $notificacao->delete();

            return redirect()->back()->with('success', 'Sucesso! Mensagem excluido!');
        } else {
            return redirect()->back()->with('error', 'Mensagem não encontrado.');
        }
    }
}
