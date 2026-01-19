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
    Schema::create('detailkirim', function (Blueprint $table) {
        $table->id();
        $table->string('kodekirim', 20);
        $table->string('kodeproduk', 20);
        $table->integer('qty');
        $table->timestamps();

        $table->foreign('kodekirim')->references('kodekirim')->on('masterkirim')->cascadeOnDelete();
        $table->foreign('kodeproduk')->references('kodeproduk')->on('produk')->cascadeOnDelete();
    });

}
    public function down(): void
    {
        Schema::dropIfExists('detailkirim');
    }
};
