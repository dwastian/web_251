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
        Schema::create('produk', function (Blueprint $table) {
            $table->string('kodeproduk', 20)->primary();
            $table->string('nama', 200);
            $table->string('satuan', 15);
            $table->double('harga');
            $table->string('gambar', 200);
            $table->string('kodegudang', 20);

            $table->foreign('kodegudang')->references('kodegudang')->on('gudang');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
