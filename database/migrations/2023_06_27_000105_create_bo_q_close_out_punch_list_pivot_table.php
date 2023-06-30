<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoQCloseOutPunchListPivotTable extends Migration
{
    public function up()
    {
        Schema::create('bo_q_close_out_punch_list', function (Blueprint $table) {
            $table->unsignedInteger('close_out_punch_list_id');
            $table->foreign('close_out_punch_list_id', 'close_out_punch_list_id_fk_7907350')->references('id')->on('close_out_punch_lists')->onDelete('cascade');
            $table->unsignedInteger('bo_q_id');
            $table->foreign('bo_q_id', 'bo_q_id_fk_7907350')->references('id')->on('bo_qs')->onDelete('cascade');
        });
    }
}
