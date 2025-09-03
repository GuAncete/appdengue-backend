<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoStatus extends Model
{
    use HasFactory;

    protected $table = 'historico_status';
    protected $primaryKey = 'IdHistorico';
    public $timestamps = false;

    protected $fillable = [
        'IdDenuncia', 'IdUsuario', 'StatusAnterior', 'StatusNovo', 'DataAlteracao', 'Observacao'
    ];

    public function denuncia()
    {
        return $this->belongsTo(Denuncia::class, 'IdDenuncia', 'IdDenuncia');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'IdUsuario', 'id');
    }
}
