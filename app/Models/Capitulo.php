<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Capitulo extends Model
{
    use HasFactory;

    protected $table = 'capitulos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'document_id',
    ];

    public function documento() : BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function componentes(): HasMany
    {
        return $this->hasMany(Componente::class, 'capitulos_id', 'id');
    }
}
