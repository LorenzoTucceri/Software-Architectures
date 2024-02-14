<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController
{
    public function __invoke(Request $request)
    {
       /* User::create([
            'name' => 'Ale',
            'surname' => 'Pio',
            'email' => 'ale@libero.it',
            'password' => Hash::make("12345"),
            'role' => 'Admin'
        ]);
       */

        $credentials = $request->only('email', 'password');

        if ($auth = Auth::attempt($credentials)) {


            // L'utente è autenticato, reindirizzare a una pagina appropriata
            return redirect()->route('welcome');

        }

        return back()->withErrors(['email' => 'Credenziali non valide']);
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }

}