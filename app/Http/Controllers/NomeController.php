<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Lista;
use App\Models\Nome;

use Dompdf\Dompdf;
use Dompdf\Options;
use Jurosh\PDFMerge\PDFMerger;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

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
            'cpfcnpj'            => 'required',
            'nome'               => 'required|string|max:255',
            'dataNascimento'     => 'required|string|max:20',
            'email'              => 'string|max:100',
            'whatsapp'           => 'string|max:20',
            'documento_com_foto' => 'image'
        ]);

        $image = $request->file('documento_com_foto');
        $imageData = file_get_contents($image);
        $base64Image = base64_encode($imageData);

        $options = new Options();
        $options->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($options);
        $views = ['documento.fichaAssociativa'];
        $html = '';

        foreach ($views as $view) {
            $html .= View::make($view, ['data' => $request, 'base64Image' => $base64Image])->render();
        }

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfContent = $dompdf->output();

        $tempFileName = tempnam(sys_get_temp_dir(), 'ficha_');
        file_put_contents($tempFileName, $pdfContent);

        $nomeArquivo = 'ficha_' . uniqid() . '.pdf';
        Storage::disk('public')->put('documentos/' . $nomeArquivo, file_get_contents($tempFileName));

        $ficha_associativa = Storage::url('documentos/' . $nomeArquivo);
        $vendaData = $this->prepareVendaData($request, $request->id_vendedor, $ficha_associativa);

        $venda = Nome::create($vendaData);

        // $documentoBase64 = base64_encode(file_get_contents(storage_path('app/' . $nomeArquivo)));
        // $this->enviaFicha($documentoBase64, $venda->whatsapp);
        if (!$venda) {
            return view('dashboard.vendas.venda', ['produto' => $request->produto, 'error' => 'Não foi possível realizar essa venda, tente novamente mais tarde!']);
        }

        return view('dashboard.vendas.documento', ['produto' => $request->produto, 'nome' => $venda, 'success' => 'Agora, envie os documentos necessários!']);
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

        $pdfFileUnificadoPath = public_path('storage/documentos/' . uniqid('documento_final_') . '.pdf');

        foreach ($pdfFiles as $pdfFilePath) {
            $pdf->addPDF($pdfFilePath, 'all', 'vertical');
        }

        $pdf->merge('file', $pdfFileUnificadoPath);

        $nome->documento_final = 'storage/documentos/' . basename($pdfFileUnificadoPath);
        $nome->save();

        return redirect()->route('dashboard')->with('success', 'Cadastro de Associado Completo!');
    }

    private function prepareVendaData(Request $request, $id, $ficha) {
        $lista = Lista::where('status', 1)->first();
        $vendaData = ['id_vendedor' => $id];
        $vendaData['nome'] = $request->nome;
        $vendaData['cpfcnpj'] = preg_replace('/[^0-9]/', '', $request->cpfcnpj);
        $vendaData['whatsapp'] = preg_replace('/[^0-9]/', '', $request->whatsapp);
        $vendaData['email'] = $request->email;
        $vendaData['id_produto'] = $request->id_produto;
        $vendaData['id_lista'] = $lista->id;
        $vendaData['valor'] = 0;
        $vendaData['ficha_associativa'] = $ficha;

        if ($request->documento_com_foto) {
            $nomeArquivo = uniqid('documento_') . '.' . $request->documento_com_foto->getClientOriginalExtension();
            $caminhoImagem = $request->documento_com_foto->storeAs('public/documentos', $nomeArquivo);

            $vendaData['documento_com_foto'] = Storage::url($caminhoImagem);
        }

        return $vendaData;
    }
}