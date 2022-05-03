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
     * @OA\Get(
     *      path="/api/peticiones",
     *      tags={"Peticiones"},
     *      summary="Get list of peticiones",
     *      description="Returns list of petiiones",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#Peticiones")
     *       ),
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
     *      description="Returns list of peticiones of the logged in user",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#Peticiones")
     *       ),
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
     *         response=200,
     *         description="Mostrar la petición."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
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
 *      path="/api/peticiones/{id}",
 *      tags={"Peticiones"},
 *      summary="Update existing peticione",
 *      description="Returns updated peticione data",
 *      @OA\Parameter(
 *          name="id",
 *          description="Peticione id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              ref="#Peticione")
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="#Peticione")
 *       ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad Request"
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Resource Not Found"
 *      )
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