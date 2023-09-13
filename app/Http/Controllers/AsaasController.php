<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;

use App\Models\Vendas;
use App\Models\Ebook;
use App\Models\User;

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
            }

            $ebook = Ebook::where('id_pay', $id)->first();
            if ($ebook) {
                $ebook->status = 'PAYMENT_CONFIRMED';
                $ebook->save();

                if($this->trataProduto($ebook->produto, $ebook->id_vendedor, $ebook->email)){
                    return response()->json(['status' => 'success', 'response' => 'Venda Confirmada!']);
                } else {
                    return response()->json(['status' => 'error', 'response' => 'Venda Confirmada, mas sem tratamento!']);
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Webhook não utilizado']);
    }

    public function trataProduto($produto, $vendedor, $email = null) {
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
            case 2:
                if ($email) {
                    $pdfPath = public_path('arquivos/ebook.zip');
                    if (file_exists($pdfPath)) {
                        Mail::raw('Mensagem de e-mail', function ($message) use ($email, $pdfPath) {
                            $message->to($email)->subject('Olá! Seu Ebook chegou!');
                            $message->attach($pdfPath);
                        });
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
                break;
            case 3:
                if ($email) {
                    $pdfPath = public_path('arquivos/combo.zip');
                    if (file_exists($pdfPath)) {
                        Mail::raw('Mensagem de e-mail', function ($message) use ($email, $pdfPath) {
                            $message->to($email)->subject('Olá! Seu Ebook chegou!');
                            $message->attach($pdfPath);
                        });
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
                break;
            default:
                return true;
        }
    }

    public function geraPagamento(Request $request) {
        $jsonData = $request->json()->all();

        $email     = $jsonData['email'];
        $cpf   = $jsonData['cpf'];
        $pagamento = $jsonData['pagamento'];
        $produto   = $jsonData['produto'];
        $vendedor   = $jsonData['vendedor'];

        $client = new Client();

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'access_token' => env('API_TOKEN'),
            ],
            'json' => [
                'name'      => 'Cliente Diego Brazil',
                'cpfCnpj'   => $cpf,
            ],
        ];

        $response = $client->post(env('API_URL_ASSAS') . 'api/v3/customers', $options);
        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        if ($response->getStatusCode() === 200) {
            $options = [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'access_token' => env('API_TOKEN'),
                ],
                'json' => [
                    'customer' => $data['id'],
                    'billingType' => $pagamento,
                    'value' => $produto == 1 ? 27 : ($produto == 2 ? 47 : 27),
                    'dueDate' => Carbon::now()->addDay()->format('Y-m-d'),
                    'description' => 'BRA - Produtos',
                ],
            ];

            $response = $client->post(env('API_URL_ASSAS') . 'api/v3/payments', $options);
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if ($response->getStatusCode() === 200) {

                $ebook = Ebook::create([
                    'cpf' => $cpf,
                    'email' => $email,
                    'valor' => $produto == 1 ? 27 : ($produto == 2 ? 47 : 27),
                    'produto' => $produto,
                    'status' => 'PENDING_PAY',
                    'id_pay' => $data['id'],
                    'id_vendedor' => $vendedor,
                ]);
                return [
                    'paymentLink' => $data['invoiceUrl'],
                ];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
















