<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * @OA\GET(
     *     path="/api/users/firmas",
     *     summary="Devuelve las peticiones firmadas por el usuario logueado",
     *     tags={"Users"},
     *     @OA\Response(
     *        response="200",
     *        description="Successful response",
     *          @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="peticiones",
     *                type="array",
     *                example={{"id":1,"titulo":"lalala3","descripcion":"lalala3","destinatario":"lalala3","firmantes":0,"estado":"pendiente","user_id":1,"categoria_id":1,"image":null,"created_at":"2022-05-03T07:47:11.000000Z","updated_at":"2022-05-03T07:50:31.000000Z"},{"id":11,"titulo":"lalala2","descripcion":"lalala2","destinatario":"lalala2","firmantes":0,"estado":"pendiente","user_id":1,"categoria_id":1,"image":null,"created_at":"2022-05-03T07:47:22.000000Z","updated_at":"2022-05-03T07:47:22.000000Z"},{"id":21,"titulo":"lalala3","descripcion":"lalala3","destinatario":"lalala3","firmantes":0,"estado":"pendiente","user_id":1,"categoria_id":1,"image":null,"created_at":"2022-05-03T08:06:02.000000Z","updated_at":"2022-05-03T08:06:02.000000Z"}},
     *                @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example=""
     *                      ),
     *                      @OA\Property(
     *                         property="titulo",
     *                         type="string",
     *                         example=""
     *                      ),
     *                      @OA\Property(
     *                         property="descripcion",
     *                         type="string",
     *                         example=""
     *                      ),
     *                      @OA\Property(
     *                         property="destinatario",
     *                         type="string",
     *                         example=""
     *                      ),
     *                      @OA\Property(
     *                         property="firmantes",
     *                         type="number",
     *                         example=""
     *                      ),
     *                      @OA\Property(
     *                         property="estado",
     *                         type="string",
     *                         example=""
     *                      ),
     *                      @OA\Property(
     *                         property="user_id",
     *                         type="number",
     *                         example=""
     *                      ),
     *                      @OA\Property(
     *                         property="categoria_id",
     *                         type="number",
     *                         example=""
     *                      ),
     *                ),
     *             ),
     *        ),
     *     ),
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