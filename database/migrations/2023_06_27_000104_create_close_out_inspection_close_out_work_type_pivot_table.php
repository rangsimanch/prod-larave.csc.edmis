<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseOutInspectionCloseOutWorkTypePivotTable extends Migration
{
    public function up()
    {
        Schema::create('close_out_inspection_close_out_work_type', function (Blueprint $table) {
            $table->unsignedInteger('close_out_inspection_id');
            $table->foreign('close_out_inspection_id', 'close_out_inspection_id_fk_7907321')->references('id')->on('close_out_inspections')->onDelete('cascade');
            $table->unsignedInteger('close_out_work_type_id');
            $table->foreign('close_out_work_type_id', 'close_out_work_type_id_fk_7907321')->references('id')->on('close_out_work_types')->onDelete('cascade');
        });
    }
}
