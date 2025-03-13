<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function pessoas()
    {
        return $this->belongsToMany(Pessoa::class, 'cargo_pessoa')
                    ->withPivot('data_inicio', 'data_fim')
                    ->withTimestamps();
    }
}