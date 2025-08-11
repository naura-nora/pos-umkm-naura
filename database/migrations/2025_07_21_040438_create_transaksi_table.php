migration table transaksi

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('kode')->unique();
            $table->string('nama_pelanggan');
            $table->integer('total');
            $table->integer('bayar');
            $table->integer('kembalian');
            $table->date('tanggal');
            $table->string('status')->default('Lunas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};