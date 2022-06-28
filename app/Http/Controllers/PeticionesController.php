<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\File;
use App\Models\Peticione;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PeticionesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'list']]);
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
     * @OA\GET(
     *     path="/api/peticiones/list",
     *     summary="Devuelve las peticiones paginadas",
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

    function list(Request $request) {

        $peticiones = Peticione::jsonPaginate();
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

        return $peticiones->toJson(JSON_OBJECT_AS_ARRAY);
        //return "[{'kk':'kk'}]"; //$peticiones;
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
        if ($request->user()->cannot('update', $peticion)) {
            return response()->json(['message' => 'No estás autorizado para realizar esta acción'], 403);
        }
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
     *    @OA\RequestPara(
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
    public function salvar(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'titulo' => 'required|max:255',
                'descripcion' => 'required',
                'destinatario' => 'required',
                'categoria_id' => 'required',
                //'file' => 'required',
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $category = Categoria::findOrFail($request->input('categoria_id'));
        $user = Auth::user(); //asociarlo al usuario authenticado
        $user = User::findOrFail($user->id);

        $peticion = new Peticione();
        $peticion->titulo = $request->input('titulo');
        $peticion->descripcion = $request->input('descripcion');
        $peticion->destinatario = $request->input('destinatario');

        $peticion->user()->associate($user);
        $peticion->categoria()->associate($category);

        $peticion->firmantes = 0;
        $peticion->estado = 'pendiente';
        $res = $peticion->save();

        if ($res) {

            return response()->json(['message' => 'Peticion creada satisfactioriamente', 'peticion' => $peticion], 201);
        }
        return response()->json(['message' => 'Error creando la peticion'], 500);

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
     *    @OA\RequestPara(
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
        $validator = Validator::make($request->all(),
            [
                'titulo' => 'required|max:255',
                'descripcion' => 'required',
                'destinatario' => 'required',
                'categoria_id' => 'required',
                //'file' => 'required',
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $validator = Validator::make($request->all(),
            [
                'file' => 'required|mimes:png,jpg|max:4096',
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $category = Categoria::findOrFail($request->input('categoria_id'));
        $user = Auth::user(); //asociarlo al usuario authenticado
        $user = User::findOrFail($user->id);

        $peticion = new Peticione();
        $peticion->titulo = $request->input('titulo');
        $peticion->descripcion = $request->input('descripcion');
        $peticion->destinatario = $request->input('destinatario');

        $peticion->user()->associate($user);
        $peticion->categoria()->associate($category);

        $peticion->firmantes = 0;
        $peticion->estado = 'pendiente';
        $res = $peticion->save();

        if ($res) {
            $res_file = $this->fileUpload($request, $peticion->id);
            if ($res_file) {
                return response()->json(['message' => 'Peticion creada satisfactioriamente', 'peticion' => $peticion], 201);
            }
            return response()->json(['message' => 'Error creando la peticion'], 500);
        }
        return response()->json(['message' => 'Error creando la peticion'], 500);

    }

    public function fileUpload(Request $req, $peticione_id = null)
    {
        $file = $req->file('file');
        $fileModel = new File;
        $fileModel->peticione_id = $peticione_id;
        if ($req->file('file')) {
            //return $req->file('file');

            $filename = $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move('images', $filename);

            //$filePath = $req->file('file')->storeAs('images/peticiones', $fileName, 'local');
            //return $filePath;
            $fileModel->name = $filename;
            $fileModel->file_path = '/public/images/' . $filename;
            $res = $fileModel->save();
            return $fileModel;
            if ($res) {
                return 0;
            } else {
                return 1;
            }
        }
        return 1;
    }

    /**
     * @OA\Put(
     *     path="/api/peticiones/firmar/{id}",
     *     tags={"Peticiones"},
     *     summary="Servicio para firmar peticiones",
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
     *    response=200,
     *    description="Signed correctly",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Peticion firmada satisfactioriamente"),
     *     )
     * ),
     * @OA\Response(
     *    response=500,
     *    description="Could not be signed",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="La petición no se ha podido firmar")
     *        )
     *     )
     * )
     *     )
     * )
     */

    public function firmar(Request $request, $id)
    {
        try {
            $peticion = Peticione::findOrFail($id);
            $user = Auth::user();
            $firmas = $peticion->firmas;
            foreach ($firmas as $firma) {
                if ($firma->id == $user->id) {
                    return response()->json(['message' => 'Ya has firmado esta petición'], 403);
                }
            }
            $user_id = [$user->id];
            $peticion->firmas()->attach($user_id);
            $peticion->firmantes = $peticion->firmantes + 1;
            $peticion->save();

        } catch (\Throwable$th) {
            return response()->json(['message' => 'La petición no se ha podido firmar'], 500);

        }

        return response()->json(['message' => 'Peticion firmada satisfactioriamente', 'peticion' => $peticion], 201);

    }

    /**
     * @OA\Put(
     *     path="/api/peticiones/estado/{id}",
     *     tags={"Peticiones"},
     *     summary="Servicio para firmar peticiones",
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
     *    response=200,
     *    description="Signed correctly",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Peticion actualizada satisfactioriamente"),
     *     )
     * ),
     * @OA\Response(
     *    response=500,
     *    description="Could not be signed",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Error actualizando la peticion")
     *        )
     *     )
     * )
     *     )
     * )
     */

    public function cambiarEstado(Request $request, $id)
    {
        $peticion = Peticione::findOrFail($id);
        if ($request->user()->cannot('cambiarEstado', $peticion)) {
            return response()->json(['message' => 'No estás autorizado para realizar esta acción'], 403);
        }
        $peticion->estado = 'aceptada';
        $res = $peticion->save();
        if ($res) {
            return response()->json(['message' => 'Peticion actualizada satisfactioriamente', 'peticion' => $peticion], 201);
        }
        return response()->json(['message' => 'Error actualizando la peticion'], 500);

        //return $peticion;

    }

    /**
     * @OA\Delete(
     *     path="/api/peticiones/{id}",
     *     tags={"Peticiones"},
     *     summary="Servicio para firmar peticiones",
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
     *    response=200,
     *    description="Signed correctly",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Peticion eliminada satisfactioriamente"),
     *     )
     * ),
     * @OA\Response(
     *    response=500,
     *    description="Could not be signed",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Error eliminando la peticion")
     *        )
     *     )
     * )
     *     )
     * )
     */

    public function destroy(Request $request, $id)
    {
        $peticion = Peticione::findOrFail($id);
        if ($request->user()->cannot('delete', $peticion)) {
            return response()->json(['message' => 'No estás autorizado para realizar esta acción'], 403);
        }
        Storage::delete($peticion->files);
        $res = $peticion->delete();

        if ($res) {
            return response()->json(['message' => 'Peticion eliminada satisfactioriamente'], 201);
        }
        return response()->json(['message' => 'Error eliminando la peticion'], 500);

        // return $peticion;
    }
}