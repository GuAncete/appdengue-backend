<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Lista usuários",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de usuários"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $users = User::orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'status' => "ok",
            'users' => $users
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Exibe um usuário pelo ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'status' => "ok",
            'users' => $user
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Cria um novo usuário",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","phone"},
     *             @OA\Property(property="name", type="string", example="João da Silva"),
     *             @OA\Property(property="email", type="string", example="joao@email.com"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *             @OA\Property(property="phone", type="string", example="11999999999")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao criar usuário"
     *     )
     * )
     */
    public function store(UserRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone,
                'user_tipo' => 3
            ]);

            DB::commit();
            return response()->json([
                'status' => "ok",
                'user' => $user,
                'message' => "Usuário cadastrado com sucesso"
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => "error",
                'message' => "Usuário não cadastrado"
            ], 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Atualiza um usuário existente",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="João da Silva"),
     *             @OA\Property(property="email", type="string", example="joao@email.com"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *             @OA\Property(property="phone", type="string", example="11999999999")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao atualizar usuário"
     *     )
     * )
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone,
                'user_tipo' => $request->user_tipo
            ]);

            DB::commit();

            return response()->json([
                'status' => "ok",
                'user' => $user,
                'message' => 'Usuário atualizado com sucesso'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => "error",
                'message' => "Usuário não atualizado"
            ], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Exclui um usuário pelo ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário excluído com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao excluir usuário"
     *     )
     * )
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();

            return response()->json([
                'status' => "ok",
                'user' => $user,
                'message' => "Usuário excluído com sucesso"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => "error",
                'message' => "Usuário não excluído"
            ], 400);
        }
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => 'ok',
                'message' => __($status), // retorna a mensagem traduzida do Laravel
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => __($status),
        ], 400);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json([
                'status' => true,
                'message' => "Senha resetada com sucesso"
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => "Erro ao resetar a senha"
        ], 400);
    }
}
