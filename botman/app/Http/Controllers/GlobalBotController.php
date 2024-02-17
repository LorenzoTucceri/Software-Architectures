<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Bot\BotController;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class GlobalBotController extends BotController
{
    public function __invoke()
    {
    }

    public function setSessionData(Request $request)
    {
        $sessione = Session::where('id', 1)->first();

        $sessione->user_id = $request->input('userId');
        if($request->input('chatId'))
            $sessione->chat_id = $request->input('chatId');

        $sessione->save();

        return response()->json(['success' => true]);
    }


        public function newChat()
        {
            $sessione = Session::where('id', 1)->first();
            $botman = $this->botman;

            $botman->hears('{message}', function ($botman, $message) use ($sessione) {

                $pythonUrl = 'http://127.0.0.1:5000/start-conversation';
                $response = Http::post($pythonUrl, $message);

                $data = $response->json();
                $botman->reply($data['reply']);


              /*  $chat = Chat::create([
                    'user_id' => $sessione->user_id,
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

    public function oldChat()
    {
        $sessione = Session::where('id', 1)->first();
        $botman = $this->botman;


        $botman->hears('{message}', function($botman, $message) use($sessione) {

                $pythonUrl = 'http://127.0.0.1:5000/start-conversation';
                $response = Http::post($pythonUrl, $message);

                $data = $response->json();
                $botman->reply($data['reply']);

              /*  Message::create([
                    'chat_id' => $sessione->chat_id,
                    'question' => $message,
                    'answer' => $data['reply']
                ]);
              */

        });
        $botman->listen();
    }

    public function loadChat(){
        $sessione = Session::where('id', 1)->first();
        $messages = Message::where("chat_id", $sessione->chat_id)->get();

        foreach($messages as $message){
            $this->botman->hears($message->question, function ($botman,$message) {
                $botman->reply($message->answer);
            });
        }
    }




}
