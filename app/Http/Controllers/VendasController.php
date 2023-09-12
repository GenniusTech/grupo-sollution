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
        $dataInicio = $request->input('data_inicio');
        $dataFim = $request->input('data_fim');
        $status = $request->input('status');

        $vendasQuery = Vendas::query();

        if (!is_null($id)) {
            $vendasQuery->where('id_produto', $id);
        }

        if ($user->tipo != 1) {
            $vendasQuery->where('id_vendedor', $user->id);
        }

        if ($status != 0) {
            $vendasQuery->where('status_pay', $status);
        }

        if ($dataInicio && $dataFim) {
            $dataInicio = Carbon::parse($dataInicio);
            $dataFim = Carbon::parse($dataFim);

            $vendasQuery->whereBetween('updated_at', [$dataInicio, $dataFim]);
        }

        $vendas = $vendasQuery->latest()->get();

        return view('dashboard.vendas.vendas', [
            'vendas' => $vendas,
            'produto' => $id
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
        if (!$paymentLinkData) {
            return $this->handlePaymentGenerationError($request->franquia, $id);
        }

        $venda->id_pay = $paymentLinkData['paymentId'];
        $venda->status_pay = 'PENDING';
        $venda->save();

        return redirect()->away($paymentLinkData['paymentLink']);
    }

    public function vendaDireta($id = null)
    {
        return view('dashboard.vendas.venda', [ 'produto' => $id ]);
    }

    public function action_vendaDireta(Request $request) {
        $request->validate([
            'cpf'               => 'required',
            'cliente'           => 'required|string|max:255',
            'dataNascimento'    => 'required|string|max:20',
            'email'             => 'string|max:100',
            'whatsapp'          => 'required|string|max:20',
        ]);

        $vendaData = $this->prepareVendaData($request, $request->id_vendedor);
        $venda = Vendas::create($vendaData);

        if (!$venda) {
            return view('dashboard.vendas.vendas.venda', [ 'produto' => $request->produto, 'msgErro' => 'Não foi possivel realizar essa venda, tente novamente mais tarde!']);
        }

        if($request->assas == 'true'){
            $paymentLinkData = $this->geraPagamentoAssas($venda->nome, $venda->cpf, $venda->id_produto, $venda->valor);

            if (!$paymentLinkData) {
                return view('dashboard.vendas.vendas.venda', [ 'produto' => $request->produto, 'msgErro' => 'Não foi possivel gerar o link de pagamento para essa venda, tente novamente mais tarde!']);
            }

            $venda->id_pay = $paymentLinkData['paymentId'];
            $venda->status_pay = 'PENDING';
            $venda->save();

            return view('dashboard.vendas.vendas.venda', [ 'produto' => $request->produto, 'msgSuccesso' => 'Venda realizada com sucesso, envie o link de Pagamento para o cliente: <a id="copiarLink(this)" data-link="'.$paymentLinkData['paymentLink'].'">'.$paymentLinkData['paymentLink'].'</a>' ]);
        }

        $venda->status_pay = 'PENDING';
        $venda->save();

        return view('dashboard.vendas.venda', [ 'produto' => $request->produto, 'msgSuccesso' => 'Venda realizada com sucesso, não foi gerado link de Pagamento!']);
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
            return $e->getMessage();
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
