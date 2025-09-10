<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resolucao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResolucaoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/resolucoes",
     *     summary="Lista todas as resoluções",
     *     tags={"Resolucao"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de resoluções retornada com sucesso"
     *     )
     * )
     */
    public function index(){

        $resolucoes = Resolucao::orderBy('IdResolucao', 'DESC')->get();

        return response()->json([
            'status' => "ok",
            'resolucoes' => $resolucoes
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/resolucoes",
     *     summary="Cadastra uma nova resolução de denúncia",
     *     tags={"Resolucao"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"IdUsuario", "IdDenuncia"},
     *             @OA\Property(property="IdUsuario", type="integer", description="ID do usuário que resolveu"),
     *             @OA\Property(property="IdDenuncia", type="integer", description="ID da denúncia resolvida"),
     *             @OA\Property(property="Observacao", type="string", description="Observação da resolução", nullable=true),
     *             @OA\Property(property="DataResolucao", type="string", format="date-time", description="Data da resolução (opcional, se não enviado será a data atual)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Resolução cadastrada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao cadastrar resolução"
     *     )
     * )
     */
    public function store(Request $request){
        DB::beginTransaction();

        try{
            $resolucao = Resolucao::create([
                'IdUsuario' => $request->IdUsuario,
                'IdDenuncia' => $request->IdDenuncia,
                'DataResolucao' => $request->DataResolucao,
                'DataResolucao' => now(),
                'Observacao' => $request->Observacao,
            ]);

            DB::commit();
            return response()->json([
                'status' => "ok",
                'resolucao' => $resolucao,
                'message' => "Resolução cadastrada com sucesso"
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => "error",
                'message' => "Erro ao cadastrar resolução: " . $e->getMessage()
            ], 400);
        }
    }
}
