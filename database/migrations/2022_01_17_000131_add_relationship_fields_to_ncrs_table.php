<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToNcrsTable extends Migration
{
    public function up()
    {
        Schema::table('ncrs', function (Blueprint $table) {
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_5806042')->references('id')->on('construction_contracts');
            $table->unsignedBigInteger('corresponding_ncn_id')->nullable();
            $table->foreign('corresponding_ncn_id', 'corresponding_ncn_fk_5805828')->references('id')->on('ncns');
            $table->unsignedInteger('prepared_by_id')->nullable();
            $table->foreign('prepared_by_id', 'prepared_by_fk_5805840')->references('id')->on('users');
            $table->unsignedInteger('contractor_manager_id')->nullable();
            $table->foreign('contractor_manager_id', 'contractor_manager_fk_5805841')->references('id')->on('users');
            $table->unsignedInteger('issue_by_id')->nullable();
            $table->foreign('issue_by_id', 'issue_by_fk_5805843')->references('id')->on('users');
            $table->unsignedInteger('construction_specialist_id')->nullable();
            $table->foreign('construction_specialist_id', 'construction_specialist_fk_5805844')->references('id')->on('users');
            $table->unsignedInteger('related_specialist_id')->nullable();
            $table->foreign('related_specialist_id', 'related_specialist_fk_5805845')->references('id')->on('users');
            $table->unsignedInteger('leader_id')->nullable();
            $table->foreign('leader_id', 'leader_fk_5805846')->references('id')->on('users');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_5805850')->references('id')->on('teams');
        });
    }
}
