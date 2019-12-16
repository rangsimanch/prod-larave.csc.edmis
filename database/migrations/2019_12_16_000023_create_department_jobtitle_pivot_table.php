<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentJobtitlePivotTable extends Migration
{
    public function up()
    {
        Schema::create('department_jobtitle', function (Blueprint $table) {
            $table->unsignedInteger('jobtitle_id');

            $table->foreign('jobtitle_id', 'jobtitle_id_fk_578782')->references('id')->on('jobtitles')->onDelete('cascade');

            $table->unsignedInteger('department_id');

            $table->foreign('department_id', 'department_id_fk_578782')->references('id')->on('departments')->onDelete('cascade');
        });
    }
}
