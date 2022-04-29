<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function peticionesFirmadas(Request $request)
    {
        $id = Auth::id();
        //$id = 2;
        $usuario = User::findOrFail($id);
        $peticiones = $usuario->firmas;
        return $peticiones;
        //return view('peticiones.index', compact('peticiones'));

    }
}