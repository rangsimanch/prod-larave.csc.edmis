<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddDrawingAsBuildPlanPivotTable extends Migration
{
    public function up()
    {
        Schema::create('add_drawing_as_build_plan', function (Blueprint $table) {
            $table->unsignedInteger('as_build_plan_id');
            $table->foreign('as_build_plan_id', 'as_build_plan_id_fk_1566146')->references('id')->on('as_build_plans')->onDelete('cascade');
            $table->unsignedInteger('add_drawing_id');
            $table->foreign('add_drawing_id', 'add_drawing_id_fk_1566146')->references('id')->on('add_drawings')->onDelete('cascade');
        });
    }
}
