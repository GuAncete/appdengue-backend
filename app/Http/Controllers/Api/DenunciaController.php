<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Denuncia;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DenunciaController extends Controller
{
    public function index(){

        $denuncias = Denuncia::orderBy('IdDenuncia', 'DESC')->get();

        return response()->json([
            'status' => "ok",
            'denuncias' => $denuncias
        ], 200);
    }

    public function show(Denuncia $denuncia){
        return response()->json([
            'status' => "ok",
            'denuncia' => $denuncia
        ], 200);
    }

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
}
