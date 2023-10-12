<?php

namespace App\Http\Controllers;

use App\Models\Lista;
use App\Models\User;
use App\Models\Nome;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function dashboard () {

        $users = auth()->user();
        $usuarios = User::all();
        $lista = Lista::where('status', 1)->first();

        if($users->tipo == 1) {
            if ($lista) {
                $nomes = Nome::where('id_lista', $lista->id)->limit(30)->get();
            } else {
                $nomes = Nome::take(30)->get();
            }
        } else {
            if ($lista) {
                $nomes = Nome::where('id_vendedor', $users->id)
                    ->where('id_lista', $lista->id)
                    ->limit(30)
                    ->get();
            } else {
                $nomes = Nome::where('id_vendedor', $users->id)
                    ->limit(30)
                    ->get();
            }
        }
        $totalNomes = $nomes->count();

        return view('dashboard.index', [
            'nomes'      => $nomes,
            'lista'      => $lista,
            'totalNomes' => $totalNomes,
            'usuarios'   => $usuarios->count()
        ]);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
