<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailToPessoasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->string('email')->nullable()->after('nome'); // Adiciona a coluna `email`
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->dropColumn('email'); // Remove a coluna `email` se a migration for revertida
        });
    }
}