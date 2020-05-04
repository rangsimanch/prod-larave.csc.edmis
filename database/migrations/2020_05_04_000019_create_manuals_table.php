<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualsTable extends Migration
{
    public function up()
    {
        Schema::create('manuals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }
}
