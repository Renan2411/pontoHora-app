<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pausa extends Model
{
    use HasFactory;

    protected $fillable = [
        'ponto_id', 'inicio', 'fim', 'total'
    ];

    public function ponto(){
        return $this->belongsTo(Ponto::class);
    }
}
