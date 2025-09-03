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
    public function index()
    {
        $fotos = Foto::with('denuncia')->get();
        return response()->json($fotos);
    }

    public function show($id)
    {
        $foto = Foto::with('denuncia')->find($id);

        if (!$foto) {
            return response()->json(['error' => 'Foto não encontrada'], 404);
        }

        return response()->json($foto);
    }

    public function store(Request $request, $idDenuncia)
    {
        if (!$request->hasFile('foto')) {
            return response()->json(['error' => 'Nenhuma foto enviada'], 400);
        }

        $path = $request->file('foto')->store('denuncias', 'public');

        $foto = Foto::create([
            'IdDenuncia' => $idDenuncia,
            'Caminho' => $path
        ]);

        return response()->json([
            'message' => 'Foto salva com sucesso',
            'foto' => $foto,
            'url' => url('storage/' . $foto->Caminho)
        ], 201);
    }

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
