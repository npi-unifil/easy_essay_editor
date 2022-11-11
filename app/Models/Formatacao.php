<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Formatacao extends Model
{
    use HasFactory;

    protected $table = 'templates';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'margem-superior',
        'margem-inferior',
        'margem-direita',
        'margem-esquerda',
        'tamanho-fonte',
        'tamanho-fonte-titulo',
        'alinhamento-texto',
        'alinhamento-titulo',
        'espacamento-texto',
        'formato-titulo',
        'formato-texto',
        'peso-texto',
        'peso-titulo',
        'template_id'
    ];

    public function template() : BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
