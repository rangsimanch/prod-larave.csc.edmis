<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSrtInputDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('srt_input_documents', function (Blueprint $table) {
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_2137162')->references('id')->on('teams');
            $table->unsignedInteger('docuement_status_id')->nullable();
            $table->foreign('docuement_status_id', 'docuement_status_fk_2137472')->references('id')->on('srt_document_statuses');
        });
    }
}
