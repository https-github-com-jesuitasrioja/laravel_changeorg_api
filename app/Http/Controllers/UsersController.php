<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users/firmas",
     *     summary="Mostrar peticiones firmadas por el usuario logueado",
     *     @OA\Response(
     *         response=200,
     *         description="Mostrar todas las peticiones."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
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