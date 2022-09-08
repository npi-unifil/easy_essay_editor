<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'nome',
        'users_id'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function componentes()
    {
        return $this->hasMany(Componente::class, 'document_id', 'id');
    }

}
