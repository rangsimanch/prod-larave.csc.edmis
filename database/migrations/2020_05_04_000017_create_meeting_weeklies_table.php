<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingWeekliesTable extends Migration
{
    public function up()
    {
        Schema::create('meeting_weeklies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('meeting_name')->nullable();
            $table->string('meeting_no')->nullable();
            $table->date('date')->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }
}
