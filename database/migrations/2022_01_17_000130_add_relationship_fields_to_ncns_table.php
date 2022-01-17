<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToNcnsTable extends Migration
{
    public function up()
    {
        Schema::table('ncns', function (Blueprint $table) {
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_5806041')->references('id')->on('construction_contracts');
            $table->unsignedInteger('issue_by_id')->nullable();
            $table->foreign('issue_by_id', 'issue_by_fk_5805786')->references('id')->on('users');
            $table->unsignedInteger('related_specialist_id')->nullable();
            $table->foreign('related_specialist_id', 'related_specialist_fk_5805787')->references('id')->on('users');
            $table->unsignedInteger('leader_id')->nullable();
            $table->foreign('leader_id', 'leader_fk_5805788')->references('id')->on('users');
            $table->unsignedInteger('construction_specialist_id')->nullable();
            $table->foreign('construction_specialist_id', 'construction_specialist_fk_5805789')->references('id')->on('users');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_5805793')->references('id')->on('teams');
        });
    }
}
