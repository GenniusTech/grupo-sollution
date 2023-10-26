<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Lista;
use App\Models\Nome;

use Jurosh\PDFMerge\PDFMerger;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NomeController extends Controller
{
    public function vendaDireta($id = null) {
        $listaAberta = Lista::where('status', 1)->first();
        if($listaAberta) {
            return view('dashboard.vendas.venda', ['produto' => $id]);
        }

        return redirect()->back()->with('error', 'Nenhuma lista disponível!');
    }

    public function cadastraNome(Request $request) {
        $request->validate([
            'cpfcnpj' => 'required',
            'nome' => 'required|string|max:255',
            'dataNascimento' => 'required|string|max:20',
            'email' => 'string|max:100',
            'whatsapp' => 'string|max:20',
        ]);

        $vendaData = $this->prepareVendaData($request, $request->id_vendedor);
        $venda = Nome::create($vendaData);

        if (!$venda) {
            return view('dashboard.vendas.venda', ['produto' => $request->produto, 'error' => 'Não foi possível cadastrar esse Associado, tente novamente mais tarde!']);
        }

        return view('dashboard.vendas.documento', ['produto' => $request->produto, 'nome' => $venda, 'success' => 'Agora, envie os documentos necessários!']);
    }

    private function prepareVendaData(Request $request, $id) {
        $lista = Lista::where('status', 1)->first();
        $vendaData = ['id_vendedor' => $id];
        $vendaData['nome'] = $request->nome;
        $vendaData['cpfcnpj'] = preg_replace('/[^0-9]/', '', $request->cpfcnpj);
        $vendaData['whatsapp'] = preg_replace('/[^0-9]/', '', $request->whatsapp);
        $vendaData['email'] = $request->email;
        $vendaData['id_produto'] = $request->id_produto;
        $vendaData['id_lista'] = $lista->id;
        $vendaData['valor'] = $request->valor;

        return $vendaData;
    }

    public function excluiNome(Request $request) {

        $nome = Nome::where('id', $request->id)->first();
        if ($nome) {
            $nome->delete();
            return redirect()->back()->with('success', 'Associado excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Usuário não encontrado!');
    }

    public function cadastraDocumento(Request $request) {
        $nome = Nome::where('id', $request->id)->first();
        $pdf = new PDFMerger;
        $pdfFiles = [];

        if ($request->ficha_associativa) {
            $nomeArquivo = uniqid('ficha_') . '.' . $request->ficha_associativa->getClientOriginalExtension();
            $caminhoImagem = $request->ficha_associativa->storeAs('public/documentos', $nomeArquivo);

            $nome->ficha_associativa = Storage::url($caminhoImagem);
            $nome->save();
            $pdfFiles[] = public_path('storage/documentos/'.$nomeArquivo);
        }

        if ($request->cartao_cnpj) {
            $nomeArquivo = uniqid('cartao_cnpj_') . '.' . $request->cartao_cnpj->getClientOriginalExtension();
            $caminhoImagem = $request->cartao_cnpj->storeAs('public/documentos', $nomeArquivo);

            $nome->cartao_cnpj = Storage::url($caminhoImagem);
            $nome->save();
            $pdfFiles[] = public_path('storage/documentos/'.$nomeArquivo);
        }

        if ($request->consulta) {
            $nomeArquivo = uniqid('consulta_') . '.' . $request->consulta->getClientOriginalExtension();
            $caminhoImagem = $request->consulta->storeAs('public/documentos', $nomeArquivo);

            $nome->consulta = Storage::url($caminhoImagem);
            $nome->save();
            $pdfFiles[] = public_path('storage/documentos/'.$nomeArquivo);
        }

        if ($request->documento_com_foto) {
            $nomeArquivo = uniqid('documento_com_foto_') . '.' . $request->documento_com_foto->getClientOriginalExtension();
            $caminhoImagem = $request->consulta->storeAs('public/documentos', $nomeArquivo);

            $nome->documento_com_foto = Storage::url($caminhoImagem);
            $nome->save();
            $pdfFiles[] = public_path('storage/documentos/'.$nomeArquivo);
        }

        $pdfFileUnificadoPath = public_path('storage/documentos/' . uniqid('documento_final_') . '.pdf');

        foreach ($pdfFiles as $pdfFilePath) {
            $pdf->addPDF($pdfFilePath, 'all', 'vertical');
        }

        $pdf->merge('file', $pdfFileUnificadoPath);

        $nome->documento_final = 'storage/documentos/' . basename($pdfFileUnificadoPath);
        $nome->save();

        return redirect()->route('dashboard')->with('success', 'Cadastro de Associado Completo!');
    }

    public function consulta(Request $request) {
        $cpfCnpj = $request->cpfCnpj;

        $client = new Client([
            'verify' => false,
        ]);

        try {
            if (strlen($cpfCnpj) > 12) {
                $response = $client->get('https://hyb.com.br/curl_cnpj.php?action=acessa_curl&cnpj=' . $cpfCnpj);
            } else {
                $response = $client->get('https://api.bronxservices.net/consulta/cGhzbG9mYzpKb3JnZTAxMDEu/serasa/cpf/' . $cpfCnpj);
            }
            $data = json_decode($response->getBody(), true);
            return response()->json($data);
        } catch (GuzzleException $e) {
            return response()->json(['message' => 'Erro na solicitação'], 500);
        }
    }
}
