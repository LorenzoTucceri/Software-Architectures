<?php

namespace App\Http\Controllers;


use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController
{
    public function __invoke(Request $request)
    {
     /*   User::create([
            'name' => 'Ale',
            'surname' => 'Pio',
            'email' => 'ale@libero.it',
            'password' => Hash::make("12345678"),
            'role' => 'Admin'
        ]); */


        $credentials = $request->only('email', 'password');

        if ($auth = Auth::attempt($credentials)) {


            // L'utente è autenticato, reindirizzare a una pagina appropriata
            return redirect()->route('welcome');

        }

        return back()->withErrors(['email' => 'Credenziali non valide']);
    }



    public function showRegisterForm()
    {
        return view('auth/register');
    }

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => "user",

        ]);

        Auth::login($user);

        return redirect()->route('welcome');
    }


    public function updateUser(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:8',

        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        if(isset($request->password))
            $user->password = Hash::make($request->password);
        $user->save();

        session()->flash('user_updated', true);

        return redirect()->back()->with('success', 'User information updated successfully.');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }

}