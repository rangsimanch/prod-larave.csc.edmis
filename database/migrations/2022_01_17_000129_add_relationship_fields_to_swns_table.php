<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSwnsTable extends Migration
{
    public function up()
    {
        Schema::table('swns', function (Blueprint $table) {
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_5806040')->references('id')->on('construction_contracts');
            $table->unsignedInteger('issue_by_id')->nullable();
            $table->foreign('issue_by_id', 'issue_by_fk_5802560')->references('id')->on('users');
            $table->unsignedInteger('responsible_id')->nullable();
            $table->foreign('responsible_id', 'responsible_fk_5802566')->references('id')->on('users');
            $table->unsignedInteger('related_specialist_id')->nullable();
            $table->foreign('related_specialist_id', 'related_specialist_fk_5805426')->references('id')->on('users');
            $table->unsignedInteger('leader_id')->nullable();
            $table->foreign('leader_id', 'leader_fk_5805427')->references('id')->on('users');
            $table->unsignedInteger('construction_specialist_id')->nullable();
            $table->foreign('construction_specialist_id', 'construction_specialist_fk_5805428')->references('id')->on('users');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_5802572')->references('id')->on('teams');
        });
    }
}
