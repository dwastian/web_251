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
        Schema::create('masterkirim', function (Blueprint $table) {
            $table->string('kodekirim', 20)->primary();
            $table->date('tglkirim');
            $table->string('nopol', 10);
            $table->double('totalqty');

            $table->foreign('nopol')->references('nopol')->on('kendaraan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('masterkirim');
    }
};
