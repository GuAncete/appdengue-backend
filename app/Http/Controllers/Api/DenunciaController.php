<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Denuncia;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DenunciaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/denuncias",
     *     summary="Lista todas as denúncias",
     *     tags={"Denuncia"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de denúncias retornada com sucesso"
     *     )
     * )
     */
    public function index(){

        $denuncias = Denuncia::with('fotos')->orderBy('DataCriacao', 'desc')->get();

        
        return response()->json([
            'status' => "ok",
            'denuncias' => $denuncias
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/denuncias/{id}",
     *     summary="Exibe uma denúncia específica",
     *     tags={"Denuncia"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da denúncia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Denúncia retornada com sucesso"
     *     )
     * )
     */
    public function show(Denuncia $denuncia){

        $denuncia->load('fotos');
        return response()->json([
            'status' => "ok",
            'denuncia' => $denuncia
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/denuncias",
     *     summary="Cadastra uma nova denúncia",
     *     tags={"Denuncia"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"IdUsuario","TipoFoco","Descricao","Longitude","Latitude","Status"},
     *             @OA\Property(property="IdUsuario", type="integer"),
     *             @OA\Property(property="TipoFoco", type="string"),
     *             @OA\Property(property="Descricao", type="string"),
     *             @OA\Property(property="Longitude", type="number", format="float"),
     *             @OA\Property(property="Latitude", type="number", format="float"),
     *             @OA\Property(property="Status", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Denúncia cadastrada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao cadastrar denúncia"
     *     )
     * )
     */
    public function store(Request $request){
        DB::beginTransaction();

        try{
            $denuncia = Denuncia::create([
                'IdUsuario' => $request->IdUsuario,
                'TipoFoco' => $request->TipoFoco,
                'Descricao' => $request->Descricao,
                'Longitude' => $request->Longitude,
                'Latitude' => $request->Latitude,
                'Status' => $request->Status,
                'DataCriacao' => now()
            ]);

            DB::commit();
            return response()->json([
                'status' => "ok",
                'denuncia' => $denuncia,
                'message' => "Denúncia cadastrada com sucesso"
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => "error",
                'message' => "Erro ao cadastrar denúncia: " . $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/denuncias/{id}",
     *     summary="Atualiza uma denúncia existente",
     *     tags={"Denuncia"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da denúncia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"IdUsuario","Descricao","Endereco","Status"},
     *             @OA\Property(property="IdUsuario", type="integer"),
     *             @OA\Property(property="Descricao", type="string"),
     *             @OA\Property(property="Endereco", type="string"),
     *             @OA\Property(property="Status", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Denúncia atualizada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao atualizar denúncia"
     *     )
     * )
     */
    public function update(Request $request, Denuncia $denuncia){
        DB::beginTransaction();

        try{
            $denuncia->update([
                'IdUsuario' => $request->IdUsuario,
                'Descricao' => $request->Descricao,
                'Endereco' => $request->Endereco,
                'Status' => $request->Status
            ]);

            DB::commit();
            return response()->json([
                'status' => "ok",
                'denuncia' => $denuncia,
                'message' => "Denúncia atualizada com sucesso"
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => "error",
                'message' => "Erro ao atualizar denúncia: " . $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/denuncias/{id}",
     *     summary="Remove uma denúncia",
     *     tags={"Denuncia"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da denúncia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Denúncia removida com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao remover denúncia"
     *     )
     * )
     */
    public function destroy(Denuncia $denuncia){

        try{
            $denuncia->delete();

            return response()->json([
                'status' => "ok",
                'message' => "Denúncia removida com sucesso"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => "error",
                'message' => "Erro ao remover denúncia: " . $e->getMessage()
            ], 400);
        }
    }

    /**
 * Marca uma denúncia como finalizada.
 */
public function finalizar(Denuncia $denuncia)
    {
        // Verifica se a denúncia já foi finalizada para não sobrescrever a data
        if ($denuncia->data_fim) {
            return response()->json([
                'message' => 'Esta denúncia já foi finalizada anteriormente.'
            ], 409); // 409 Conflict
        }

        // Atualiza a coluna 'data_fim' com a data e hora atuais
        $denuncia->data_fim = now();
        $denuncia->save();

        // Retorna uma resposta de sucesso
        return response()->json([
            'message' => 'Denúncia finalizada com sucesso!',
            'denuncia' => $denuncia
        ]);
    }

        public function relatorio()
    {
        // 1. Pega o usuário que está autenticado
        $user = Auth::user();

        // 2. Trava de Segurança: Verifica se o usuário é um administrador (tipo 1)
        if ($user->user_tipo != 1) {
            return response()->json(['message' => 'Acesso não autorizado.'], 403); // 403 Forbidden
        }

        // 3. Busca todas as denúncias, ordenando pelas mais recentes
        $denuncias = Denuncia::orderBy('DataCriacao', 'desc')->get();

        // 4. Mapeia os resultados para adicionar o tempo de resolução
        $relatorio = $denuncias->map(function ($denuncia) {
            $tempoResolucao = null;
            if ($denuncia->data_fim) {
                $criacao = Carbon::parse($denuncia->DataCriacao);
                $finalizacao = Carbon::parse($denuncia->data_fim);
                // Calcula a diferença em um formato legível para humanos
                $tempoResolucao = $criacao->diffForHumans($finalizacao, true); // Ex: "5 dias", "2 horas"
            }

            return [
                'id' => $denuncia->IdDenuncia,
                'descricao' => $denuncia->Descricao,
                'status' => $denuncia->Status,
                'data_criacao' => $denuncia->DataCriacao,
                'data_finalizacao' => $denuncia->data_fim,
                'tempo_resolucao' => $tempoResolucao
            ];
        });

        // 5. Retorna o relatório completo
        return response()->json($relatorio);
    }

}
