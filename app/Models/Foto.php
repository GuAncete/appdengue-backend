<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    use HasFactory;

    protected $table = 'fotos';
    protected $primaryKey = 'IdFoto';
    public $timestamps = false;

    protected $fillable = ['IdDenuncia', 'CaminhoArquivo'];

    public function denuncia()
    {
        return $this->belongsTo(Denuncia::class, 'IdDenuncia', 'IdDenuncia');
    }
}
