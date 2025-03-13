<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('cargo_pessoa', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pessoa_id')->constrained()->onDelete('cascade');
        $table->foreignId('cargo_id')->constrained()->onDelete('cascade');
        $table->date('data_inicio');
        $table->date('data_fim')->nullable();
        $table->timestamps();
    });
}
};
