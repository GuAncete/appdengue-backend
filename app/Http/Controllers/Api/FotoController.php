<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FotoRequest;
use App\Models\Foto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FotoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/fotos",
     *     summary="Lista todas as fotos",
     *     tags={"Foto"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de fotos retornada com sucesso"
     *     )
     * )
     */
    public function index()
    {
        $fotos = Foto::with('denuncia')->get();
        return response()->json($fotos);
    }

    /**
     * @OA\Get(
     *     path="/api/fotos/{id}",
     *     summary="Exibe uma foto específica",
     *     tags={"Foto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da foto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Foto retornada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Foto não encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $foto = Foto::with('denuncia')->find($id);

        if (!$foto) {
            return response()->json(['error' => 'Foto não encontrada'], 404);
        }

        return response()->json($foto);
    }

    /**
     * @OA\Post(
     *     path="/api/fotos/{idDenuncia}",
     *     summary="Cadastra uma nova foto para uma denúncia",
     *     tags={"Foto"},
     *     @OA\Parameter(
     *         name="idDenuncia",
     *         in="path",
     *         required=true,
     *         description="ID da denúncia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"foto"},
     *                 @OA\Property(
     *                     property="foto",
     *                     type="string",
     *                     format="binary",
     *                     description="Arquivo da foto"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Foto salva com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Nenhuma foto enviada"
     *     )
     * )
     */
    public function store(Request $request, $idDenuncia)
    {
        if (!$request->hasFile('foto')) {
            return response()->json(['error' => 'Nenhuma foto enviada'], 400);
        }

        $path = $request->file('foto')->store('denuncias', 'public');

        $foto = Foto::create([
            'IdDenuncia' => $idDenuncia,
            'CaminhoArquivo' => $path
        ]);

        return response()->json([
            'message' => 'Foto salva com sucesso',
            'foto' => $foto,
            'url' => url('storage/' . $foto->Caminho)
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/fotos/{id}",
     *     summary="Atualiza uma foto existente",
     *     tags={"Foto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da foto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="foto",
     *                     type="string",
     *                     format="binary",
     *                     description="Arquivo da nova foto"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Foto atualizada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Foto não encontrada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $foto = Foto::find($id);

        if (!$foto) {
            return response()->json(['error' => 'Foto não encontrada'], 404);
        }

        if ($request->hasFile('foto')) {
            // Deleta a foto antiga
            if ($foto->Caminho && Storage::disk('public')->exists($foto->Caminho)) {
                Storage::disk('public')->delete($foto->Caminho);
            }

            // Salva a nova foto
            $path = $request->file('foto')->store('denuncias', 'public');
            $foto->Caminho = $path;
        }

        $foto->save();

        return response()->json([
            'message' => 'Foto atualizada com sucesso',
            'foto' => $foto,
            'url' => url('storage/' . $foto->Caminho)
        ]);
    }
    
    /**
     * @OA\Delete(
     *     path="/api/fotos/{id}",
     *     summary="Remove uma foto",
     *     tags={"Foto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da foto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Foto excluída com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Foto não encontrada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $foto = Foto::find($id);

        if (!$foto) {
            return response()->json(['error' => 'Foto não encontrada'], 404);
        }

        // Deleta a foto do storage
        if ($foto->Caminho && Storage::disk('public')->exists($foto->Caminho)) {
            Storage::disk('public')->delete($foto->Caminho);
        }

        $foto->delete();

        return response()->json(['message' => 'Foto excluída com sucesso']);
    }
}
