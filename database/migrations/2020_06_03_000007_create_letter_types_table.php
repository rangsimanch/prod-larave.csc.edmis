<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLetterTypesTable extends Migration
{
    public function up()
    {
        Schema::create('letter_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type_title')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
