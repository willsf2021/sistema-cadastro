<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'email'];

    public function cargos()
    {
        return $this->belongsToMany(Cargo::class, 'cargo_pessoa')
                    ->withPivot('data_inicio', 'data_fim')
                    ->withTimestamps();
    }
}