<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingOthersTable extends Migration
{
    public function up()
    {
        Schema::create('meeting_others', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_name')->nullable();
            $table->date('date')->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }
}
