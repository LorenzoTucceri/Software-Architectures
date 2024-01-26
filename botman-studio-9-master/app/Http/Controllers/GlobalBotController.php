<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use App\Http\Controllers\Bot\BotController;
use App\Http\Controllers\ApiController as ApiController;
use Illuminate\Support\Facades\Http;

class GlobalBotController extends BotController
{
    public function __invoke()
    {
        // You can access the botman object with this line
        $botman = $this->botman;

        $botman->hears('{message}', function($botman, $message) {
           // $botman->reply('Hello!');
            $pythonUrl = 'http://127.0.0.1:5000/start-conversation';
            $response = Http::post($pythonUrl, $message);

            $data = $response->json();
            $botman->reply($data['reply']);

            /*
             $botman->ask('Whats your name?', function($answer, $bot) {
                     $bot->say('Welcome '.$answer->getText());
                 });
             }); */


        });
        $botman->listen();
    }
}
