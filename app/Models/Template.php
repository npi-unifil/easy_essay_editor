<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasFactory;

    protected $table = 'templates';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome'
    ];

    public function documento(): HasMany
    {
        return $this->hasMany(Documento::class, 'templates_id', 'id');
    }

    public function formatacao(): HasMany
    {
        return $this->hasMany(Formatacao::class, 'templates_id', 'id');
    }
}
