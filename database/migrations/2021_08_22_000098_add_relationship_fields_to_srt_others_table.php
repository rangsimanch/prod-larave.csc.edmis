<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSrtOthersTable extends Migration
{
    public function up()
    {
        Schema::table('srt_others', function (Blueprint $table) {
            $table->unsignedInteger('docuement_status_id')->nullable();
            $table->foreign('docuement_status_id', 'docuement_status_fk_4695260')->references('id')->on('srt_document_statuses');
            $table->unsignedInteger('constuction_contract_id');
            $table->foreign('constuction_contract_id', 'constuction_contract_fk_4695261')->references('id')->on('construction_contracts');
            $table->unsignedInteger('from_id')->nullable();
            $table->foreign('from_id', 'from_fk_4695267')->references('id')->on('teams');
            $table->unsignedInteger('close_by_id')->nullable();
            $table->foreign('close_by_id', 'close_by_fk_4695279')->references('id')->on('users');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_4695284')->references('id')->on('teams');
        });
    }
}
