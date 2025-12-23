<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseOutDrivesTable extends Migration
{
    public function up()
    {
        Schema::create('close_out_drives', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('filename');
            $table->string('url');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
