<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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

                if($this->trataProduto($venda->id_produto, $venda->id_vendedor)){
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

    public function trataProduto($produto, $vendedor) {
        switch ($produto) {
            case 1:
                $vendedor = User::where('id', $vendedor)->first();
                $patrocinador = User::where('id', $vendedor->id_patrocinador)->first();

                //Atribui Comissão Vendedor
                $vendedor->saldo = $vendedor->saldo + $vendedor->comissao;
                $vendedor->save();
                //Atribui Comissão Patrocinador
                $patrocinador->saldo = $patrocinador->saldo + ( $patrocinador->comissao - $vendedor->comissao);
                $patrocinador->save();

                return true;
                break;
            default:
                return true;
        }
    }

}
















