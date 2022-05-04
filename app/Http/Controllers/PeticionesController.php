<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Peticione;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeticionesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }
    /**
     * @OA\GET(
     *     path="/api/peticiones",
     *     summary="Devuelve las peticiones",
     *     tags={"Peticiones"},
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

    public function index(Request $request)
    {

        $peticiones = Peticione::all();

        return $peticiones;
    }

    /**
     * @OA\Get(
     *      path="/api/mispeticiones/",
     *      tags={"Peticiones"},
     *      summary="Get list of peticiones of the logged in user",
     *      security={{"bearer_token":{}}},
     *      description="Returns list of peticiones of the logged in user",
     *      @OA\Response(
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
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function listMine(Request $request)
    {
        // parent::index()
        $user = Auth::user();
        //$id = 2;
        $peticiones = Peticione::all()->where('user_id', $user->id);
        return $peticiones;
    }

    /**
     * @OA\Get(
     *     path="/api/peticiones/{id}",
     *     tags={"Peticiones"},
     *     summary="Muestra el detalle de una petición",
     *     @OA\Parameter(
     *          name="id",
     *          description="Peticione id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *        response="200",
     *        description="Successful response",
     *          @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="peticion",
     *                type="array",
     *                example={{"id":1,"titulo":"lalala3","descripcion":"lalala3","destinatario":"lalala3","firmantes":0,"estado":"pendiente","user_id":1,"categoria_id":1,"image":null,"created_at":"2022-05-03T07:47:11.000000Z","updated_at":"2022-05-03T07:50:31.000000Z"}},
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
     *     )
     * )
     */
    public function show(Request $request, $id)
    {
        $peticion = Peticione::findOrFail($id);
        return $peticion;

    }

/*public function create(Request $request)
{

$this->authorize('create', Peticione::class);

return view('peticiones.edit-add');

}*/

/*public function edit(Request $request, $id)
{
$peticion = Peticione::findOrFail($id);
return view('peticiones.edit-add', compact('peticion'));

}*/
/**
 * @OA\Put(
 * path="/api/peticiones",
 * summary="Actualizar una peticion",
 * description="Actualizar una  peticion",
 * tags={"Peticiones"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Parámetros de la petición",
 *    @OA\JsonContent(
 *       required={"titulo","descripcion","destinatario"},
 *       @OA\Property(property="titulo", type="string", example="ejemplo1"),
 *       @OA\Property(property="descripcion", type="string", example="ejemplo1"),
 *       @OA\Property(property="destinatario", type="string", example="ejemplo1"),
 *    ),
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Updated correctly",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Peticion actualizada satisfactioriamente"),
 *     )
 * ),
 * @OA\Response(
 *    response=400,
 *    description="Could not be updated",
 *    @OA\JsonContent(
 *       @OA\Property(property="error", type="string", example="Error actualizando la peticion")
 *        )
 *     )
 * )
 */
    public function update(Request $request, $id)
    {
        $peticion = Peticione::findOrFail($id);
        //$peticion->update($request->all());
        $res = $peticion->update($request->all());

        if ($res) {
            return response()->json(['message' => 'Peticion actualizada satisfactioriamente', 'peticion' => $peticion], 201);
        }
        return response()->json(['message' => 'Error actualizando la peticion'], 500);

        //return $peticion;

    }

    /**
     * @OA\Post(
     * path="/api/peticiones",
     * summary="Anadir una nueva peticion",
     * description="Añadir una nueva peticion",
     * tags={"Peticiones"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Parámetros de la petición",
     *    @OA\JsonContent(
     *       required={"titulo","descripcion","destinatario"},
     *       @OA\Property(property="titulo", type="string", example="ejemplo1"),
     *       @OA\Property(property="descripcion", type="string", example="ejemplo1"),
     *       @OA\Property(property="destinatario", type="string", example="ejemplo1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Added correctly",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Peticion creada satisfactioriamente"),
     *     )
     * ),
     * @OA\Response(
     *    response=400,
     *    description="Could not be added",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Error creando la peticion")
     *        )
     *     )
     * )
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'destinatario' => 'required',
            //'file' => 'required',
        ]);

        //$input = $request->all();
        //$input = json_decode($request, true);

        //$input = $request;
        if ($file = $request->file('file')) {
            $name = $file->getClientOriginalName();
            $file->move('images', $name);
            $input['image'] = $name;

        }

        $category = Categoria::findOrFail($request->input('categoria_id'));
        $user = Auth::user(); //asociarlo al usuario authenticado
        $user = User::findOrFail($request->input('user_id'));

        $peticion = new Peticione();
        $peticion->titulo = $request->input('titulo');
        $peticion->descripcion = $request->input('descripcion');
        $peticion->destinatario = $request->input('destinatario');

        $peticion->user()->associate($user);
        $peticion->categoria()->associate($category);

        $peticion->firmantes = 0;
        $peticion->estado = 'pendiente';
        //$peticion->save();
        $res = $peticion->save();

        if ($res) {
            return response()->json(['message' => 'Peticion creada satisfactioriamente', 'peticion' => $peticion], 201);
        }
        return response()->json(['message' => 'Error creando la peticion'], 500);

    }

    public function firmar(Request $request, $id)
    {
        try {
            $peticion = Peticione::findOrFail($id);
            $user = Auth::user();
            //$user = 2;
            //$user_id = [$user['id']];
            $user_id = [$user->id];
            $peticion->firmas()->attach($user_id);

        } catch (\Throwable$th) {
            return response()->json(['message' => 'La petición no se ha podido firmar'], 500);

        }

        if ($peticion->firmas()) {
            return response()->json(['message' => 'Peticion firmada satisfactioriamente', 'peticion' => $peticion], 201);
        }
        return response()->json(['message' => 'La petición no se ha podido firmar'], 500);

    }

    public function cambiarEstado(Request $request, $id)
    {
        $peticion = Peticione::findOrFail($id);
        $peticion->estado = 'aceptada';
        $res = $peticion->save();
        if ($res) {
            return response()->json(['message' => 'Peticion actualizada satisfactioriamente', 'peticion' => $peticion], 201);
        }
        return response()->json(['message' => 'Error actualizando la peticion'], 500);

        //return $peticion;

    }

    public function destroy(Request $request, $id)
    {
        $peticion = Peticione::findOrFail($id);
        $res = $peticion->delete();

        if ($res) {
            return response()->json(['message' => 'Peticion eliminada satisfactioriamente'], 201);
        }
        return response()->json(['message' => 'Error eliminando la peticion'], 500);

        // return $peticion;
    }
}