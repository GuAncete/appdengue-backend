<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denuncia extends Model
{
    use HasFactory;

    // Nome da tabela (caso não siga o padrão plural em inglês)
    protected $table = 'denuncias';

    // Chave primária personalizada
    protected $primaryKey = 'IdDenuncia';

    // Se não tiver created_at / updated_at, definir como false
    public $timestamps = false;

    // Campos que podem ser preenchidos via mass assignment
    protected $fillable = [
        'IdUsuario',
        'TipoFoco',
        'Descricao',
        'Longitude',
        'Latitude',
        'Status',
        'DataCriacao',
    ];

    /**
     * Relação com Usuario (N:1)
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'IdUsuario', 'id');
    }

    /**
     * Relação com Fotos (1:N)
     */
    public function fotos()
    {
        return $this->hasMany(Foto::class, 'IdDenuncia', 'IdDenuncia');
    }

    /**
     * Relação com Resolucoes (1:N)
     */
    public function resolucoes()
    {
        return $this->hasMany(Resolucao::class, 'IdDenuncia', 'IdDenuncia');
    }

    /**
     * Relação com HistoricoStatus (1:N)
     */
    public function historicoStatus()
    {
        return $this->hasMany(HistoricoStatus::class, 'IdDenuncia', 'IdDenuncia');
    }
}
