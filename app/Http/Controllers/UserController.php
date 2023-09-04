<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        if (isset(auth()->user()->id)) {
            return redirect()->route('dashboard');
        }
        return view('index');
    }

    public function login_action(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $credentials['password'] = $credentials['password'];
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        } else {
            return redirect()->back()->withErrors(['email' => 'As credenciais fornecidas são inválidas.']);
        }
    }

    public function perfil()
    {
        $dados = Auth::User();
        return view('dashboard.perfil', ['dados' => $dados]);
    }

    public function action_perfil(Request $request)
    {
        $user = Auth::user();

        if ($request->filled('nome')) {
            $user->nome = $request->input('nome');
        }

        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect()->back()->with('success', 'Perfil atualizado com sucesso.');
    }

    public function usuario($tipo)
    {
        $usuarios = User::where('tipo', $tipo);

        return view('dashboard.gestao.usuario', [
            'usuarios' => $usuarios->get(),
        ]);
    }

    public function action_usuario(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'tipo' => 'required',
            'comissao' => 'required',
        ], [
            'cpf.unique' => 'CPF já está em uso.',
            'email.unique' => 'Email já está em uso.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
        ]);

        $patrociador = Auth::user();

        $user = new User();
        $user->nome = $validatedData['nome'];
        $user->cpf = $validatedData['cpf'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->tipo = $validatedData['tipo'];
        $user->comissao = $validatedData['comissao'];
        $user->saldo = 0;
        $user->id_patrocinador = $patrociador->id;
        $user->save();

        return redirect()->back()->with('success', 'Usuário cadastrado com sucesso!');
    }
}
