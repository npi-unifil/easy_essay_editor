<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referencia extends Model
{
    use HasFactory;

    protected $table = 'referencias';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'sobrenome',
        'titulo',
        'subtitulo',
        'edicao',
        'local',
        'editora',
        'ano',
        'pagina',
        'site',
        'acessado',
        'document_id'
    ];

    public function documento() : BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }
}
