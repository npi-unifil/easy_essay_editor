<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Componente extends Model
{
    use HasFactory;

    protected $table = 'componentes';

    protected $fillable = [
        'name',
        'conteudo',
        'component_order',
        'object_id',
        'capitulos_id'
    ];

    public function capitulos() : BelongsTo
    {
        return $this->belongsTo(Capitulo::class);
    }


}
