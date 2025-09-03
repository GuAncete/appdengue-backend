<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resolucao extends Model
{
    use HasFactory;

    protected $table = 'resolucoes';
    protected $primaryKey = 'IdResolucao';
    public $timestamps = false;

    protected $fillable = ['IdDenuncia', 'IdUsuario', 'DataResolucao', 'Observacao'];

    public function denuncia()
    {
        return $this->belongsTo(Denuncia::class, 'IdDenuncia', 'IdDenuncia');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'IdUsuario', 'id');
    }
}
