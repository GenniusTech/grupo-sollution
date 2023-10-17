<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


use App\Models\Marketing;

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

    public function cadastraMateriais(Request $request) {

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'descricao' => 'required',
            'id_produto' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Preencha os dados!');
        }

        $arquivoUrl = null;

        if ($request->hasFile('arquivo')) {
            $validator = Validator::make($request->all(), [
                'arquivo' => 'file|max:2048',
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
}
