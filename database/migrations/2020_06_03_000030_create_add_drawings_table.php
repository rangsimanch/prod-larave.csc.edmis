<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddDrawingsTable extends Migration
{
    public function up()
    {
        Schema::create('add_drawings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('drawing_title')->nullable();
            $table->string('coding_of_drawing')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
