<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;

use App\Models\Vendas;

class VendasController extends Controller
{

    public function getVendas(Request $request, $id = null)
    {
        $user = auth()->user();
        $produto = $id;
        $dataInicio = $request->input('data_inicio');
        $dataFim = $request->input('data_fim');
        $status = $request->input('status');

        $vendasQuery = Vendas::where('id_vendedor', $user->id);

        if ($produto) {
            $vendasQuery->where('id_produto', $produto);
        }

        if ($status) {
            $vendasQuery->where('status_pay', $status);
        }

        if ($dataInicio && $dataFim) {
            $dataInicio = Carbon::parse($dataInicio);
            $dataFim = Carbon::parse($dataFim);

            $vendasQuery->whereBetween('updated_at', [$dataInicio, $dataFim]);
        }

        $vendas = $vendasQuery->latest()->get();

        return view('dashboard.vendas', [
            'vendas' => $vendas,
            'produto' => $produto
        ]);
    }

    public function vender(Request $request, $id)
    {
        $request->validate([
            'cpf'               => 'required|string|max:15',
            'cliente'           => 'required|string|max:255',
            'dataNascimento'    => 'required|string|max:20',
            'email'             => 'string|max:100',
            'whatsapp'          => 'required|string|max:20',
        ]);

        $vendaData = $this->prepareVendaData($request, $id);
        $venda = Vendas::create($vendaData);

        if (!$venda) {
            return $this->handleVendaCreationError($request->franquia);
        }

        $paymentLinkData = $this->geraPagamentoAssas($venda->nome, $venda->cpf, $venda->id_produto, $venda->valor);
        var_dump($paymentLinkData);
        // if (!$paymentLinkData) {
        //     return $this->handlePaymentGenerationError($request->franquia, $id);
        // }

        // $venda->id_pay = $paymentLinkData['paymentId'];
        // $venda->status_pay = 'PENDING';
        // $venda->save();

        // return redirect()->away($paymentLinkData['paymentLink']);
    }

    private function prepareVendaData(Request $request, $id)
    {
        $vendaData = ['id_vendedor' => $id];
        $vendaData['nome'] = $request->cliente;
        $vendaData['cpf'] = preg_replace('/[^0-9]/', '', $request->cpf);
        $vendaData['whatsapp'] = preg_replace('/[^0-9]/', '', $request->whatsapp);
        $vendaData['email'] = $request->email;
        $vendaData['id_produto'] = $request->produto;
        $vendaData['valor'] = $request->valor;

        return $vendaData;
    }

    private function handleVendaCreationError($franquia)
    {
        return redirect()->route($franquia)->withErrors(['https://meucontatoai.com//campanhalex"']);
    }

    private function handlePaymentGenerationError($franquia, $id)
    {
        return redirect()->route($franquia, ['id' => $id])->withErrors(['https://meucontatoai.com//campanhalex'])->withInput();
    }

    public function geraPagamentoAssas($nome, $cpf, $produto, $valor)
    {
        try {
            $customerId = $this->criarClienteAssas($nome, $cpf);

            if (!$customerId) {
                throw new \Exception('Falha ao criar cliente no Assas.');
            }

            $paymentData = $this->criarPagamentoAssas($customerId, $produto, $valor);

            if (!$paymentData) {
                throw new \Exception('Falha ao gerar pagamento no Assas.');
            }

            return $paymentData;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function criarClienteAssas($nome, $cpf)
    {
        try {
            $client = new Client();

            $options = [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'access_token' => env('API_TOKEN'),
                ],
                'json' => [
                    'name'      => $nome,
                    'cpfCnpj'   => $cpf,
                ],
            ];

            $response = $client->post(env('API_URL_ASSAS') . 'api/v3/customers', $options);

            $body = (string) $response->getBody();

            $data = json_decode($body, true);

            if ($response->getStatusCode() === 200) {
                return $data['id'];
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    private function criarPagamentoAssas($customerId, $produto, $valor)
    {
        try {
            $client = new Client();

            $options = [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'access_token' => env('API_TOKEN'),
                ],
                'json' => [
                    'customer' => $customerId,
                    'billingType' => 'PIX',
                    'value' => $valor,
                    'dueDate' => Carbon::now()->addDay()->format('Y-m-d'),
                    'description' => 'Grupo Sollution - Produtos',
                ],
            ];

            $response = $client->post(env('API_URL_ASSAS') . 'api/v3/payments', $options);

            $body = (string) $response->getBody();

            $data = json_decode($body, true);

            if ($response->getStatusCode() === 200) {
                return [
                    'paymentId' => $data['id'],
                    'customer' => $data['customer'],
                    'paymentLink' => $data['invoiceUrl'],
                ];
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
