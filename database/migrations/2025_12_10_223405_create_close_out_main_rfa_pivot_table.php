<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseOutMainRfaPivotTable extends Migration
{
    public function up()
    {
        Schema::create('close_out_main_rfa', function (Blueprint $table) {
            $table->unsignedInteger('close_out_main_id');
            $table->foreign('close_out_main_id', 'close_out_main_id_fk_10776283')->references('id')->on('close_out_mains')->onDelete('cascade');
            $table->unsignedInteger('rfa_id');
            $table->foreign('rfa_id', 'rfa_id_fk_10776283')->references('id')->on('rfas')->onDelete('cascade');
        });
    }
}
