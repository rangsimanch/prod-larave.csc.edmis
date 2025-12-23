<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseOutDescriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('close_out_descriptions', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('subject');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
