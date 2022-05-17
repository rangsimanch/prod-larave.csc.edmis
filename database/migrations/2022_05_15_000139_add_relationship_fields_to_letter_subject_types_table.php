<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToLetterSubjectTypesTable extends Migration
{
    public function up()
    {
        Schema::table('letter_subject_types', function (Blueprint $table) {
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_6605380')->references('id')->on('teams');
        });
    }
}