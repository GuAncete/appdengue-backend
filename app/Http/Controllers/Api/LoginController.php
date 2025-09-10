<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Realiza o login do usuário",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Login realizado com sucesso, retorna token e dados do usuário"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Credenciais inválidas"
     *     )
     * )
     */
    public function login(Request $request)
    {
        //Valida os dados do usuario
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            
            //Recupera o usuario autenticado
            $user = Auth::user();

            $token = $request->user()->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'token' => $token,
                'user' => $user
            ], 201);
        
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Credenciais inválidas'
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout/{id}",
     *     summary="Realiza o logout do usuário",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário deslogado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao deslogar o usuário"
     *     )
     * )
     */
    public function logout(User $user)
    {
        try{    

            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Usuário deslogado com sucesso'
            ], 200);

        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Erro ao deslogar o usuário'
            ], 400);
        }
    }
}
