<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLetterSubjectTypesTable extends Migration
{
    public function up()
    {
        Schema::create('letter_subject_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('subject_name');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
