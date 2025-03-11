<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoCargo extends Model
{
    use HasFactory;
    protected $fillable = ['pessoa_id', 'cargo_id', 'data_inicio', 'data_fim'];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
