<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseOutLocationCloseOutPunchListPivotTable extends Migration
{
    public function up()
    {
        Schema::create('close_out_location_close_out_punch_list', function (Blueprint $table) {
            $table->unsignedInteger('close_out_punch_list_id');
            $table->foreign('close_out_punch_list_id', 'close_out_punch_list_id_fk_7907351')->references('id')->on('close_out_punch_lists')->onDelete('cascade');
            $table->unsignedInteger('close_out_location_id');
            $table->foreign('close_out_location_id', 'close_out_location_id_fk_7907351')->references('id')->on('close_out_locations')->onDelete('cascade');
        });
    }
}
