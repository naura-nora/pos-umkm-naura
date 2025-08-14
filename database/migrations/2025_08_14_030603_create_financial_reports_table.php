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
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->string('description');
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 15, 2);
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('financial_reports');
    }
};
