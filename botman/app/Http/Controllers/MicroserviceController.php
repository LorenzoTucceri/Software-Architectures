<?php

namespace App\Http\Controllers;

use App\Models\Microservice;
use Illuminate\Http\Request;

class MicroserviceController extends Controller
{
    public function store(Request $request)
    {
        // Validazione dei dati del form
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Creazione di un nuovo microservizio
        Microservice::create([
            'name' => $validatedData['name'],
        ]);

        // Reindirizzamento alla pagina dopo l'aggiunta del servizio
        return back()->with('success', 'Microservice added successfully!');
    }
}
