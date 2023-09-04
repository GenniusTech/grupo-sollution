<?php

namespace App\Http\Controllers;

use App\Models\Saques;
use App\Models\Vendas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class FinanceiroController extends Controller
{
    public function index() {
        $user   = Auth::id();
        $saques = Saques::where('id_usuario', $user)->get();

        $vendaCOUNT = Vendas::where('id_vendedor', $user)->where('status_pay', 'PAYMENT_CONFIRMED')->count();
        $vendaSUM   = Vendas::where('id_vendedor', $user)->where('status_pay', 'PAYMENT_CONFIRMED')->sum('valor');

        return view('dashboard.financeiro.saque', ['saques' => $saques, 'vendaCOUNT' => $vendaCOUNT, 'vendaSUM' => $vendaSUM]);
    }

    public function saque(Request $request) {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'valor' => [
                'required',
                'numeric',
                'max:' . $user->saldo,
            ],
        ], [
            'valor.required' => 'O campo valor é obrigatório.',
            'valor.numeric' => 'O campo valor deve ser um número.',
            'valor.max' => 'O valor inserido não pode ser maior do que o seu saldo disponível de R$ ' . number_format($user->saldo, 2, ',', '.'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $saque = new Saques();
        $saque->id_usuario      = $user->id;
        $saque->nome_usuario    = $user->nome;
        $saque->valor           = $request->input('valor');
        $saque->chave_pix       = $user->chave_pix;
        $saque->status          = 0;
        $saque->save();

        $user->saldo =  $user->saldo - $request->input('valor');
        $user->save();

        return redirect()->back()->with('success', 'Saque solicitado com sucesso.');
    }

    public function wallet() {
        $saques = Saques::where('status', 0)->get();
        $saqueSUM = Saques::where('status', 0)->sum('valor');
        $vendaCOUNT = Vendas::where('status_pay', 'PAYMENT_CONFIRMED')->count();
        $vendaSUM   = Vendas::where('status_pay', 'PAYMENT_CONFIRMED')->sum('valor');

        return view('dashboard.financeiro.wallet', ['saques' => $saques, 'saqueSUM' => $saqueSUM, 'vendaCOUNT' => $vendaCOUNT, 'vendaSUM' => $vendaSUM]);
    }

    public function confirmaPagamento(Request $request) {
        $saque = Saques::find($request->input('id'));

        if (!$saque) {
            return redirect()->back()->with('error', 'Saque não encontrado.');
        }

        $saque->status =  1;
        $saque->save();

        return redirect()->back()->with('success', 'Saque enviado com sucesso.');
    }

}
