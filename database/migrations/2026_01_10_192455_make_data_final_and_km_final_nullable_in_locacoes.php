<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Torna as colunas NULLABLE no MySQL
        DB::statement('ALTER TABLE locacoes MODIFY data_final_realizado_periodo DATETIME NULL');
        DB::statement('ALTER TABLE locacoes MODIFY km_final INT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Volta a ser NOT NULL
        DB::statement('ALTER TABLE locacoes MODIFY data_final_realizado_periodo DATETIME NOT NULL');
        DB::statement('ALTER TABLE locacoes MODIFY km_final INT NOT NULL');
    }
};