<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestForInspectionsTable extends Migration
{
    public function up()
    {
        Schema::create('request_for_inspections', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('type_of_work')->nullable();
            $table->string('subject')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('location')->nullable();
            $table->date('submittal_date')->nullable();
            $table->date('replied_date')->nullable();
            $table->string('ipa')->nullable();
            $table->longText('comment')->nullable();
            $table->integer('end_loop')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
