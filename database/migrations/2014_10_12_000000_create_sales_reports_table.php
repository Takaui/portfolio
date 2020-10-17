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
            $table->integer('client_id');
            $table->integer('tapeM-price');
            $table->integer('tapeM-count');
            $table->integer('tapeL-price');
            $table->integer('tapeL-count');
            $table->integer('pantsM-price');
            $table->integer('pantsM-count');
            $table->integer('pantsL-price');
            $table->integer('pantsL-count');
            $table->integer('pad300-price');
            $table->integer('pad300-count');
            $table->integer('pad400-price');
            $table->integer('pad400-count');
            $table->integer('pad600-price');
            $table->integer('pad600-count');
            $table->integer('pad800-price');
            $table->integer('pad800-count');
            $table->integer('pad1000-price');
            $table->integer('pad1000-count');
            $table->integer('pad1200-price');
            $table->integer('pad1200-count');
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
