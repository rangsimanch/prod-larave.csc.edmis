<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfatypesTable extends Migration
{
    public function up()
    {
        Schema::create('rfatypes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('type_name')->nullable();

            $table->string('type_code')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
