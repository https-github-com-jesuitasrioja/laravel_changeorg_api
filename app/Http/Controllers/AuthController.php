<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Sign in",
     * description="Login by email, password",
     * tags={"Authentication"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     *    @OA\Response(
     *    response=200,
     *    description="Good credentials",
     *     @OA\JsonContent(
     *       @OA\Property(property="access_token", type="token", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2xhcmF2ZWwtY2hhbmdlb3JnLWFwaS5oZXJva3VhcHAuY29tL2FwaS9sb2dpbiIsImlhdCI6MTY1MTU2NTE0MCwiZXhwIjoxNjUxNTY4NzQwLCJuYmYiOjE2NTE1NjUxNDAsImp0aSI6Ik1RdlBhdG1JTXZ3N29pbUkiLCJzdWIiOiIxIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.GGsbe1V4CLxBCeKDuRb-t01T1-3Ak4DF_NIJqc3Ir8U"),
     *       @OA\Property(property="token_type", type="string", example="bearer"),
     *       @OA\Property(property="expires_in", type="integer", example="3600"),
     *       @OA\Property(property="user", type="user", example={"user": {"id": 1,"name": "ilarra","email": "ilarra@gmail.com", "email_verified_at": null,"created_at": "2022-05-03T07:45:52.000000Z","updated_at": "2022-05-03T07:45:52.000000Z"}}),
     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized. Either email or password is wrong.")
     *        )
     *     )
     * ),
     * @OA\Response(
     *    response=400,
     *    description="Invalid request",
     *    @OA\JsonContent()
     * )
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        // $request->validate();
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                /*'status' => 'error',
                'message' => 'Unauthorized',*/
                'error' => 'Unauthorized. Either email or password is wrong.',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => env('JWT_TTL') * 60, //auth()->factory()->getTTL() * 60,
            'user' => $user,
        ]);

    }

    /**
     * @OA\Post(
     * path="/api/register",
     * summary="Sign up",
     * description="Regist by name, email, password",
     * tags={"Authentication"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass new user credentials",
     *    @OA\JsonContent(
     *       required={"name","email","password"},
     *       @OA\Property(property="name", type="string", example="user1"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Good credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User successfully registered"),
     *       @OA\Property(property="user", type="user", example={"user": {"id": 1,"name": "ilarra","email": "ilarra@gmail.com", "email_verified_at": null,"created_at": "2022-05-03T07:45:52.000000Z","updated_at": "2022-05-03T07:45:52.000000Z"}}),
     *     )
     * ),
     * @OA\Response(
     *    response=400,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized. Either email or password is wrong.")
     *        )
     *     )
     * )
     */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //$token = Auth::login($user);
        return response()->json([
            'message' => "User successfully registered",
            'user' => $user,
        ]);
    }
    /**
     * @OA\Post(
     * path="/api/logout",
     * summary="Logout",
     * description="Logout",
     * security={{"bearer_token":{}}},
     * tags={"Authentication"},
     * * @OA\Response(
     *    response=200,
     *    description="User correctly logged out",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User successfully signed out"),
     *     )
     * ),
     * )
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            /*'status' => 'success',
            'message' => 'Successfully logged out',*/
            'message' => 'User successfully signed out',
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/me",
     * summary="Me",
     * description="Info of the logged in user",
     * tags={"Authentication"},
     * security={{"bearer_token":{}}},
     * @OA\Response(
     *    response=200,
     *    description="User information",
     *    @OA\JsonContent(
     *       @OA\Property(property="user", type="user", example={"user": {"id": 1,"name": "ilarra","email": "ilarra@gmail.com", "email_verified_at": null,"created_at": "2022-05-03T07:45:52.000000Z","updated_at": "2022-05-03T07:45:52.000000Z"}}),
     *     )
     * ),
     * )
     */

    public function me()
    {
        return response()->json([
            /*'status' => 'success',
            'user' => Auth::user(),*/
            Auth::user(),
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/refresh",
     * summary="Sign in",
     * description="Login by email, password",
     * security={{"bearer_token":{}}},
     * tags={"Authentication"},
     * @OA\Response(
     *    response=200,
     *    description="Good credentials",
     *     @OA\JsonContent(
     *       @OA\Property(property="access_token", type="token", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2xhcmF2ZWwtY2hhbmdlb3JnLWFwaS5oZXJva3VhcHAuY29tL2FwaS9sb2dpbiIsImlhdCI6MTY1MTU2NTE0MCwiZXhwIjoxNjUxNTY4NzQwLCJuYmYiOjE2NTE1NjUxNDAsImp0aSI6Ik1RdlBhdG1JTXZ3N29pbUkiLCJzdWIiOiIxIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.GGsbe1V4CLxBCeKDuRb-t01T1-3Ak4DF_NIJqc3Ir8U"),
     *       @OA\Property(property="token_type", type="string", example="bearer"),
     *       @OA\Property(property="expires_in", type="integer", example="3600"),
     *       @OA\Property(property="user", type="user", example={"user": {"id": 1,"name": "ilarra","email": "ilarra@gmail.com", "email_verified_at": null,"created_at": "2022-05-03T07:45:52.000000Z","updated_at": "2022-05-03T07:45:52.000000Z"}}),
     *     )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized. Either email or password is wrong.")
     *        )
     *     )
     * )
     * )
     */

    public function refresh()
    {
        $user = Auth::user();
        return response()->json([
            /*'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
            'token' => Auth::refresh(),
            'type' => 'bearer',
            ],*/
            'access_token' => Auth::refresh(),
            'token_type' => 'bearer',
            'expires_in' => env('JWT_TTL') * 60,
            'user' => $user,
        ]);
    }

}