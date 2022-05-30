<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Componente extends Model
{
    use HasFactory;

    protected $table = 'componentes';

    protected $primaryKey = 'component_id';

    protected $fillable = [
        'name',
        'conteudo',
        'document_id'
    ];

    public function documento():BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }


}
