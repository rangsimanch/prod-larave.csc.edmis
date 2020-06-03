<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddLettersTable extends Migration
{
    public function up()
    {
        Schema::create('add_letters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('letter_no')->nullable();
            $table->date('sent_date');
            $table->date('received_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
