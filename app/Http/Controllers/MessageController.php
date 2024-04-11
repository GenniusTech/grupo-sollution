<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller {

    public function view() {

        return view('dashboard.message.message');
    }
    
    public function create(Request $request) {

        $message = new Message();
        $message->titulo = $request->titulo;
        $message->descricao = $request->message;
    
        if($message->save()) {
            Message::where('id', '!=', $message->id)->delete();
            return redirect()->back()->with('success', 'Mensagem atualizada com sucesso!');
        }
    
        return redirect()->back()->with('error', 'Erro ao atualizar mensagem!');
    }
    
}
