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
            $table->foreignId('transaksi_id')->nullable()->constrained();
            $table->string('source')->default('manual'); // 'manual' atau 'transaksi'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('financial_reports', function (Blueprint $table) {
            $table->dropForeign(['transaksi_id']);
            $table->dropColumn(['transaksi_id', 'source']);
        });
    }
};
