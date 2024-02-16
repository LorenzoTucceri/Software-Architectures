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
            'priority' => 'required|string|max:255',
        ]);

        try {
            // Creazione di un nuovo microservizio
            Microservice::create([
                'name' => $validatedData['name'],
                'priority' => $validatedData['priority'],
            ]);

            session()->flash('microservice_added', true);

            // Reindirizzamento alla pagina dopo l'aggiunta del servizio
            return back()->with('success', 'Microservice added successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Gestione dell'eccezione nel caso in cui il campo "priority" non sia unico nel database
            if ($e->errorInfo[1] == 1062) { // Codice di errore per la violazione di vincolo di unicità
                session()->flash('priority_error', 'Priority must be unique.');
            } else {
                // Gestione di altri errori del database
                session()->flash('general_error', 'An error occurred while adding the microservice.');
            }

            return back()->withInput();
        }
    }



    public function destroy($id)
    {
        $microservice = Microservice::findOrFail($id);
        $microservice->delete();

        session()->flash('microservice_deleted', true);


        return back()->with('success', 'Microservice deleted successfully');
    }
}
