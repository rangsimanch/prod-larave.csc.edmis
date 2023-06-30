<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseOutPunchListCloseOutWorkTypePivotTable extends Migration
{
    public function up()
    {
        Schema::create('close_out_punch_list_close_out_work_type', function (Blueprint $table) {
            $table->unsignedInteger('close_out_punch_list_id');
            $table->foreign('close_out_punch_list_id', 'close_out_punch_list_id_fk_7907353')->references('id')->on('close_out_punch_lists')->onDelete('cascade');
            $table->unsignedInteger('close_out_work_type_id');
            $table->foreign('close_out_work_type_id', 'close_out_work_type_id_fk_7907353')->references('id')->on('close_out_work_types')->onDelete('cascade');
        });
    }
}
