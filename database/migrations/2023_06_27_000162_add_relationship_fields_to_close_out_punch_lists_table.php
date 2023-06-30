<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCloseOutPunchListsTable extends Migration
{
    public function up()
    {
        Schema::table('close_out_punch_lists', function (Blueprint $table) {
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_7907346')->references('id')->on('construction_contracts');
            $table->unsignedInteger('reviewer_id')->nullable();
            $table->foreign('reviewer_id', 'reviewer_fk_7907360')->references('id')->on('users');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_7907366')->references('id')->on('teams');
        });
    }
}
