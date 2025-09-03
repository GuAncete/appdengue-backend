<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        // Recupera os dados dos usuários no banco, ordena por ID de forma decrescente e paginados
        $users = User::orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'status' => "ok",
            'users' => $users
        ], 200);
    }

    public function show(User $user): JsonResponse
    {
        // Retorna o usuario selecionado
        return response()->json([
            'status' => "ok",
            'users' => $user
        ], 200);
    }

    public function store(UserRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone
            ]);

            //Operação concluída com êxito
            DB::commit();
            return response()->json([
                'status' => "ok",
                'user' => $user,
                'message' => "Usuário cadastrado com sucesso"
            ], 201);
        } catch (Exception $e) {
            //Operação não concluida com êxito
            DB::rollBack();

            //retorna uma mensagem de erro 400
            return response()->json([
                'status' => "error",
                'message' => "Usuário não cadastrado"
            ], 400);
        }
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        //Iniciar a transação
        DB::beginTransaction();

        try{
            //Editar o registro no banco de dados
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone
            ]);

            //Operação concluída com êxito
            DB::commit();

            return response()->json([
                'status' => "ok",
                'user' => $user,
                'message' => 'Usuário atualizado com sucesso'
            ], 200);

        }catch(Exception $e){
            //Operação não concluida com êxito
            DB::rollBack();

            //retorna uma mensagem de erro 400
            return response()->json([
                'status' => "error",
                'message' => "Usuário não atualizado"
            ], 400);
        }

        return response()->json([
            'status' => "ok",
            'users' => $request,
            'message' => 'Usuário atualizado com sucesso'
        ], 200);
    }

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
}