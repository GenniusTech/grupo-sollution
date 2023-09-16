<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\Ebook as MailEbook;
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
                    return response()->json(['status' => 'success', 'response' => 'Ebook Confirmado!']);
                } else {
                    return response()->json(['status' => 'error', 'response' => 'Ebook Confirmado, mas sem tratamento!']);
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

                $vendedor->saldo = $vendedor->saldo + $vendedor->comissao;
                $vendedor->save();
                $patrocinador->saldo = $patrocinador->saldo + ( $patrocinador->comissao - $vendedor->comissao);
                $patrocinador->save();
                return true;
                break;
            case 2:
                return $this->enviaEmail($email, $produto);
                break;
            case 3:
                return $this->enviaEmail($email, $produto);
                break;
            default:
                return true;
        }
    }

    public function enviaEmail($email, $produto)
    {
        $sent = Mail::to($email)->send(new MailEbook([
            'fromName'      => 'Diego Brazil',
            'fromEmail'     => env('MAIL_USERNAME'),
            'subject'       => 'Chegou seu Ebook!',
            'message'       => 'Olá! Informamos que recebemos o seu pagamento e estamos te enviando o seu E-book. Boa Leitura!',
            'attachment'    => ($produto == 2) ? 'ebook.zip' : (($produto == 3) ? 'combo.zip' : 'ebook.zip')
        ]));

        return true;
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
















