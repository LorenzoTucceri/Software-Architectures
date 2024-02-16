<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('index', [
                'welcome' => "Hi there! I'm MiLA4U, a virtual assistant specialized in supporting the NdR Street Science Fair. How can I assist you?"]);
    }

    public function getMessagesByChatId($chatId) {
        // Recupera la chat tramite l'id
        $chat = Chat::find($chatId);

        // Verifica se la chat esiste
        if (!$chat) {
            // Gestisci il caso in cui la chat non esista
            return back();
        }

        // Recupera i messaggi associati alla chat
        $messages = Message::where('chat_id', $chatId)->get(['question', 'answer']);
        return view('index',[
            'welcome' => "Hi there! I'm MiLA4U, a virtual assistant specialized in supporting the NdR Street Science Fair. How can I assist you?"],
        ['messages' => $messages]);
    }

}
