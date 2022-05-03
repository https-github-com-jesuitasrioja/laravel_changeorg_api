<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Good credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="success")
     *        ),
     *       @OA\Property(property="user", type="user", example="Unauthorized. Either email or password is wrong.")
     *        ),
     *       @OA\Property(property="error", type="string", example="Unauthorized. Either email or password is wrong.")
     *        )
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
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
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
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);

    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user,
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            /*'status' => 'success',
            'message' => 'Successfully logged out',*/
            'message' => 'User successfully signed out',
        ]);
    }

    public function me()
    {
        return response()->json([
            /*'status' => 'success',
            'user' => Auth::user(),*/
            Auth::user(),
        ]);
    }

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
            //   'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user,
        ]);
    }

}
