<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('kendaraan', function (Blueprint $table) {
            $table->string('nopol', 10)->primary();
            $table->string('namakendaraan', 100);
            $table->string('jeniskendaraan', 100);
            $table->string('namadriver', 40);
            $table->string('kontakdriver', 15);
            $table->date('tahun');
            $table->string('kapasitas', 10);
            $table->string('foto', 200);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
    }
;
