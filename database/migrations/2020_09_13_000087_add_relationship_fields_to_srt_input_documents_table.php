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
            $table->unsignedInteger('constuction_contract_id');
            $table->foreign('constuction_contract_id', 'constuction_contract_fk_2170850')->references('id')->on('construction_contracts');
            $table->unsignedInteger('from_id')->nullable();
            $table->foreign('from_id', 'from_fk_2170852')->references('id')->on('users');
            $table->unsignedInteger('to_id')->nullable();
            $table->foreign('to_id', 'to_fk_2170853')->references('id')->on('users');
            $table->unsignedInteger('close_by_id')->nullable();
            $table->foreign('close_by_id', 'close_by_fk_2176660')->references('id')->on('users');
        });
    }
}
