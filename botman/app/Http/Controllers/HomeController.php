<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->query("fullscreen")) {
            return view('index', [
                'welcome' => 'Ciao Gay! Cosa mi vuoi chiedere?',
            ]);
        }

        return view('welcome');
    }
}
