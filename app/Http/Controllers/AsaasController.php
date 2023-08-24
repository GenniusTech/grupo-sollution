<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Dompdf\Dompdf;

use App\Models\Vendas;

class AsaasController extends Controller
{

    public function receberPagamento(Request $request) {

        $jsonData = $request->json()->all();

        if ($jsonData['event'] === 'PAYMENT_CONFIRMED' || $jsonData['event'] === 'PAYMENT_RECEIVED') {

            $id = $jsonData['payment']['id'];

            $venda = Vendas::where('id_pay', $id)->first();
            if ($venda) {

                $venda->status_pay = 'PAYMENT_CONFIRMED';
                $venda->save();

                if($this->trataProduto($venda->id_produto, $venda->id)){
                    return response()->json(['status' => 'success', 'response' => 'Venda Confirmada!']);
                } else {
                    return response()->json(['status' => 'error', 'response' => 'Venda Confirmada, mas sem tratamento!']);
                }
            } else {
                return response()->json(['status' => 'error', 'response' => 'Venda não existe!']);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Webhook não utilizado']);
    }

    public function trataProduto($produto, $venda) {
        switch ($produto) {
            case 1:
                return true;
                break;
            default:
                return true;
        }
    }

}
















