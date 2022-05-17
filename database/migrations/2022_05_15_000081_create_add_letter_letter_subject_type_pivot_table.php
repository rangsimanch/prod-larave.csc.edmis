<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddLetterLetterSubjectTypePivotTable extends Migration
{
    public function up()
    {
        Schema::create('add_letter_letter_subject_type', function (Blueprint $table) {
            $table->unsignedInteger('add_letter_id');
            $table->foreign('add_letter_id', 'add_letter_id_fk_6605503')->references('id')->on('add_letters')->onDelete('cascade');
            $table->unsignedBigInteger('letter_subject_type_id');
            $table->foreign('letter_subject_type_id', 'letter_subject_type_id_fk_6605503')->references('id')->on('letter_subject_types')->onDelete('cascade');
        });
    }
}
