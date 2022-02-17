<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'meta', 'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function pontos(){
        return $this->hasMany(Ponto::class, 'categoria_id');
    }
}
