<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Conversations\ExampleConversation;

class ApiController extends Controller
{
    private $cacheKey = 'messaggio';


    public function saveDate(String $message){
        Cache::put($this->cacheKey, $message, now()->addMinutes(10));
    }

    public function index()
    {
        $messaggio = Cache::get($this->cacheKey);
        //qui devo creare la chat (se la chat ancora non esiste) e salvare il messaggio questione

        $data = [
            'message' =>   $messaggio,
            'timestamp' => now()
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        try {
            $input = $request->json()->all();
            //qui devo aggiornare il mesaggio con la answer

            // Fai qualcosa con i dati ricevuti, ad esempio salvali nel database

            $response = [
                'message' => 'Dati ricevuti con successo!',
                'data' => $input
            ];




            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
