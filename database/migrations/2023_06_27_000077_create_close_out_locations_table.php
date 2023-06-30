<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseOutLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('close_out_locations', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('location_name');
            $table->string('location_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
