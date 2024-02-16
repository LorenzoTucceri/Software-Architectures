<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Bot\BotController;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Http;

class GlobalBotController extends BotController
{
    public function __invoke()
    {

    }


        public function newChat()
    {
        $botman = $this->botman;

        $botman->hears('{message}', function($botman, $message) {
            $pythonUrl = 'http://127.0.0.1:5000/start-conversation';
            $response = Http::post($pythonUrl, $message);

            $data = $response->json();
            $botman->reply($data['reply']);

            $chat = Chat::create([
                'user_id' => 4,
                'name' => $message
            ]);

            Message::create([
                'chat_id' => $chat->id,
                'question' => $message,
                'answer' => $data['reply']
            ]);
        });
        
        $botman->listen();


    }

    public function oldChat()
    {

        $botman = $this->botman;

        $botman->hears('{message}', function($botman, $message) {
            $pythonUrl = 'http://127.0.0.1:5000/start-conversation';
            $response = Http::post($pythonUrl, $message);

            $data = $response->json();
            $botman->reply($data['reply']);

            Message::create([
                'chat_id' => 36,
                'question' => $message,
                'answer' => $data['reply']
            ]);
        });
        $botman->listen();
    }



}
