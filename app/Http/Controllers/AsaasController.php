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
                return $this->limpaNome($venda);
                break;
            case 2:
                return $this->consulta($venda);
                break;
            case 3:
                $this->score($venda);
                break;
            default:
                break;
        }
    }

    public function limpaNome($venda) {
        $venda = Vendas::where('id', $venda)->first();
        if($venda) {
            $link = "http://127.0.0.1:8000/consulta/".$venda->cpf;
            if($this->notificaCliente($venda->whatsapp, $link)) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    public function notificaCliente($whatsapp, $link)
    {
        $client = new Client();

        $url = 'https://sub.domain.com/message/link?key=MjA3OQ==';

        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'id'            => '55' . $whatsapp,
                'textbefore'    => "Prezado Cliente, Você acaba de entrar no processo do Limpa Nome Grupo Sollution! \r\n \r\n",
                'url'           => $link,
                'textafter'     => 'No link acima você poderá acompanhar o status do seu Pedido.',
                'msdelay'       => '1000'
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);
        if (isset($responseData['id'])) {
            return true;
        } else {
            return false;
        }

    }

    public function consulta($venda) {
        $venda = Vendas::where('id', $venda)->first();
        try {
            $url = 'https://consultoria.phsldev.eu.org/painel/dashboard/consulta/consulta_2/api.php?lista='.$venda->cpf;

            $response = Http::get($url);

            if ($response->successful()) {
                $html = $response->body();

                $pdf = new Dompdf();
                $pdf->loadHtml($html);
                $pdf->render();

                $pdfPath = public_path('consultas');
                if (!File::exists($pdfPath)) {
                    File::makeDirectory($pdfPath);
                }

                $pdfFileName = $venda->id.'consulta_' . now()->format('Y-m-d') . '.pdf';
                $pdf->save($pdfPath . '/' . $pdfFileName);

                return $pdfFileName;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function score() {

    }

}
















