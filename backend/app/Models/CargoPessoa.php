<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoPessoa extends Model
{
    use HasFactory;

    protected $table = 'cargo_pessoa';

    protected $fillable = ['pessoa_id', 'cargo_id', 'data_inicio', 'data_fim'];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }
}