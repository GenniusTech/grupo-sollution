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

    public function atualizaPerfil(Request $request)
    {
        $user = Auth::user();

        if ($request->filled('nome')) {
            $user->nome = $request->input('nome');
        }

        if ($request->filled('cpfcnpj')) {
            $user->cpfcnpj = preg_replace('/[^0-9]/', '', $request->input('cpfcnpj'));
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

    public function usuario() {
        $usuarios = User::all();

        return view('dashboard.gestao.usuario', [
            'usuarios' => $usuarios,
        ]);
    }

    public function cadastraUsuario(Request $request) {
        $patrociador = Auth::user();

        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'cpfcnpj' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'tipo' => 'required',
            'valor_limpa_nome' => 'required',
        ], [
            'cpfcnpj.unique' => 'CPF/CNPJ já está em uso.',
            'email.unique' => 'Email já está em uso.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
        ]);

        $user = new User();
        $user->nome = $validatedData['nome'];
        $user->cpfcnpj = $validatedData['cpfcnpj'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->tipo = $validatedData['tipo'];
        $user->valor_limpa_nome = $validatedData['valor_limpa_nome'];
        $user->id_criador = $patrociador->id;
        $user->save();

        return redirect()->back()->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function atualizaUsuario(Request $request) {
        $usuario = User::where('id', $request->id)->first();

        if($usuario) {
            if ($request->filled('nome')) {
                $usuario->nome = $request->input('nome');
            }

            if ($request->filled('email')) {
                $usuario->email = $request->input('email');
            }

            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->input('password'));
            }

            if ($request->filled('tipo')) {
                $usuario->tipo = $request->input('tipo');
            }

            if ($request->filled('valor_limpa_nome')) {
                $usuario->valor_limpa_nome = $request->input('valor_limpa_nome');
            }

            $usuario->save();
            return redirect()->back()->with('success', 'Usuário atualizado com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Usuário não encontrado!');
        }
    }

    public function excluiUsuario(Request $request) {
        $usuario = User::where('id', $request->id)->first();

        if($usuario) {
            $usuario->delete();
            return redirect()->back()->with('success', 'Usuário excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Usuário não encontrado!');
    }
}
