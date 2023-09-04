<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function dashboard (){
        $users = auth()->user();
        $vendas = Vendas::where('id_vendedor', $users->id)->limit(30)->get();

        return view('dashboard.index', [
            'vendas' => $vendas
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
