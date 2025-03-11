<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'email'];
    // Relacionamento com HistoricoCargo
    public function historicoCargos()
    {
        return $this->hasMany(HistoricoCargo::class);
    }
}