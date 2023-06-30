<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseOutInspectionCloseOutLocationPivotTable extends Migration
{
    public function up()
    {
        Schema::create('close_out_inspection_close_out_location', function (Blueprint $table) {
            $table->unsignedInteger('close_out_inspection_id');
            $table->foreign('close_out_inspection_id', 'close_out_inspection_id_fk_7907319')->references('id')->on('close_out_inspections')->onDelete('cascade');
            $table->unsignedInteger('close_out_location_id');
            $table->foreign('close_out_location_id', 'close_out_location_id_fk_7907319')->references('id')->on('close_out_locations')->onDelete('cascade');
        });
    }
}
