<?php

namespace App\Http\Controllers;

use App\Models\Nome;

use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RelatorioController extends Controller
{
    public function geraListaExcel(Request $request) {
        $query = Nome::where('id_lista', $request->lista)->whereNotNull('documento_final');

        if ($request->usuario) {
            $query->where('id_vendedor', $request->usuario);
        }

        $nomes = $query->get();

        $dadosFormatados = [];

        foreach ($nomes as $nome) {
            $dadosFormatados[] = [
                'Nome' => $nome->nome,
                'CPF/CNPJ' => $nome->cpfcnpj,
                'Email' => $nome->email,
                'Telefone' => $nome->whatsapp,
                'Valor' => $nome->valor,
                'Cadastro' => $nome->created_at,
            ];
        }

        return $dadosFormatados;
    }

    public function geraListaZip(Request $request) {
        $query = Nome::where('id_lista', $request->lista)->whereNotNull('documento_final');

        if ($request->usuario) {
            $query->where('id_vendedor', $request->usuario);
        }

        $nomes = $query->get();

        if ($nomes->isEmpty()) {
            return redirect()->back()->with('error', 'Não há dados para gerar relatório!');
        }

        $zip = new ZipArchive;
        $zipFileName = public_path('Lista.zip');

        if ($zip->open($zipFileName, ZipArchive::CREATE) === true) {
            foreach ($nomes as $nome) {
                $clienteFolderName = Str::slug($nome->nome);
                $documentoPath = $nome->documento_final;
                $documento = str_replace('storage/documentos/', '', $documentoPath);
                $zip->addFile(public_path($documentoPath), $clienteFolderName . '/' . $documento);
            }

            $zip->close();
            return response()->download($zipFileName)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', 'Falha ao gerar relatório!');
        }
    }



}
