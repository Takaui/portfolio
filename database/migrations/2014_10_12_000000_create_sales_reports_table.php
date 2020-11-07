<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->integer('tapeM_price');
            $table->integer('tapeM_count');
            $table->integer('tapeL_price');
            $table->integer('tapeL_count');
            $table->integer('pantsM_price');
            $table->integer('pantsM_count');
            $table->integer('pantsL_price');
            $table->integer('pantsL_count');
            $table->integer('pad300_price');
            $table->integer('pad300_count');
            $table->integer('pad400_price');
            $table->integer('pad400_count');
            $table->integer('pad600_price');
            $table->integer('pad600_count');
            $table->integer('pad800_price');
            $table->integer('pad800_count');
            $table->integer('pad1000_price');
            $table->integer('pad1000_count');
            $table->integer('pad1200_price');
            $table->integer('pad1200_count');
            $table->integer('year');
            $table->integer('month');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_reports');
    }
}
