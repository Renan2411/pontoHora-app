<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponto extends Model
{
    use HasFactory;

    protected $casts = [
        'finalizado' => 'bool',
        'data' => 'date'
    ];

    protected $fillable = [
        'categoria_id', 'inicio', 'fim', 'total', 'finalizado', 'data'
    ];

    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    public function pausas(){
        return $this->hasMany(Pausa::class, 'ponto_id');
    }
}
