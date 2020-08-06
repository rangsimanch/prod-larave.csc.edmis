<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestForInspectionsTable extends Migration
{
    public function up()
    {
        Schema::create('request_for_inspections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('type_of_work')->nullable();
            $table->string('location')->nullable();
            $table->longText('comment')->nullable();
            $table->date('submittal_date')->nullable();
            $table->date('replied_date')->nullable();
            $table->string('ipa')->nullable();
            $table->integer('start_loop')->nullable();
            $table->integer('end_loop')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
