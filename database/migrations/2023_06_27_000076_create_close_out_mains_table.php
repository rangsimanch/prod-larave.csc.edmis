<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseOutMainsTable extends Migration
{
    public function up()
    {
        Schema::create('close_out_mains', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('chapter_no');
            $table->string('title');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
