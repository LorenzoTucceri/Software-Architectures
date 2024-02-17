<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Bot\BotController;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class GlobalBotController extends BotController
{
    public function __invoke()
    {

    }

    public function setSessionData(Request $request)
    {
        $userId = $request->input('userId');
        Session::put('userId', $userId);
        return response()->json(['success' => true]);
    }


        public function newChat()
        {
       
            $botman = $this->botman;

            $botman->hears('{message}', function ($botman, $message) {

                $pythonUrl = 'http://127.0.0.1:5000/start-conversation';
                $response = Http::post($pythonUrl, $message);

                $data = $response->json();
                $botman->reply($data['reply']);

             /*   $chat = Chat::create([
                    'user_id' => Session::get('userId'),
                    'name' => $message
                ]);

                Message::create([
                    'chat_id' => $chat->id,
                    'question' => $message,
                    'answer' => $data['reply']
                ]);
             */

            });


            $botman->listen();
        }

    public function oldChat(Request $request)
    {

        $botman = $this->botman;

        $botman->hears('{message}', function($botman, $message) use($request) {
            $pythonUrl = 'http://127.0.0.1:5000/start-conversation';
            $response = Http::post($pythonUrl, $message);

            $data = $response->json();
            $botman->reply($data['reply']);

        /*    Message::create([
                'chat_id' => $request->chatId,
                'question' => $message,
                'answer' => $data['reply']
            ]);
        */
        });
        $botman->listen();
    }



}
