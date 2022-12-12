<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Formatacao extends Model
{
    use HasFactory;

    protected $table = 'formatacao';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'margemSuperior',
        'margemInferior',
        'margemDireita',
        'margemEsquerda',
        'tamanhoFonte',
        'tamanhoFonteTitulo',
        'alinhamentoTexto',
        'alinhamentoTitulo',
        'espacamentoTexto',
        'formatoTitulo',
        'formatoTexto',
        'pesoTexto',
        'pesoTitulo',
        'templates_id'
    ];

    public function template() : BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
