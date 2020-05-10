<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDroneVdoRecordedsTable extends Migration
{
    public function up()
    {
        Schema::create('drone_vdo_recordeds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('work_tiltle')->nullable();
            $table->longText('details')->nullable();
            $table->date('operation_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
