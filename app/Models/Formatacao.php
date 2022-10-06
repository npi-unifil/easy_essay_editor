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
        'fonte',
        'cor',
        'tamanho-fonte',
        'tamanho-fonte-titulo',
        'alinhamento-texto',
        'espacamento-texto',
        'espacamento-citacao',
        'espacamento-paragrafo',
        'recuo-texto',
        'formato-texto',
        'espacamento-entre',
        'peso-texto',
        'template_id'
    ];

    public function template() : BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
