<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'nome',
        'users_id',
        'templates_id'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }


    public function componentes(): HasMany
    {
        return $this->hasMany(Componente::class, 'document_id', 'id');
    }

    public function referencias(): HasMany
    {
        return $this->hasMany(Referencia::class, 'document_id', 'id');
    }

}
