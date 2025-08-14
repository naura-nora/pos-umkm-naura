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
        Schema::table('financial_reports', function (Blueprint $table) {
        if (!Schema::hasColumn('financial_reports', 'transaksi_id')) {
            $table->unsignedBigInteger('transaksi_id')->nullable()->after('user_id');
            $table->foreign('transaksi_id')->references('id')->on('transaksi')->onDelete('cascade');
        }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('financial_reports', function (Blueprint $table) {
            // Hanya hapus jika kolom ada
            if (Schema::hasColumn('financial_reports', 'transaksi_id')) {
                $table->dropForeign(['transaksi_id']);
                $table->dropColumn('transaksi_id');
            }
        });
    }
};
