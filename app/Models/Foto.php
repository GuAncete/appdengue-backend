<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Foto extends Model
{
    use HasFactory;

    protected $table = 'fotos';
    protected $primaryKey = 'IdFoto';
    public $timestamps = false;

    protected $fillable = ['IdDenuncia', 'CaminhoArquivo'];

    /**
     * Anexa o atributo 'url' ao JSON do modelo.
     * Isto garante que o URL completo seja sempre enviado na resposta da API.
     */
    protected $appends = ['url'];

    public function denuncia()
    {
        return $this->belongsTo(Denuncia::class, 'IdDenuncia', 'IdDenuncia');
    }

    /**
     * Accessor que cria o URL público completo para a foto.
     * Esta é a alteração necessária para corrigir o problema.
     */
    public function getUrlAttribute()
    {
        if ($this->CaminhoArquivo) {
            return Storage::disk('public')->url($this->CaminhoArquivo);
        }
        return null;
    }
}