<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoQCloseOutInspectionPivotTable extends Migration
{
    public function up()
    {
        Schema::create('bo_q_close_out_inspection', function (Blueprint $table) {
            $table->unsignedInteger('close_out_inspection_id');
            $table->foreign('close_out_inspection_id', 'close_out_inspection_id_fk_7907318')->references('id')->on('close_out_inspections')->onDelete('cascade');
            $table->unsignedInteger('bo_q_id');
            $table->foreign('bo_q_id', 'bo_q_id_fk_7907318')->references('id')->on('bo_qs')->onDelete('cascade');
        });
    }
}
