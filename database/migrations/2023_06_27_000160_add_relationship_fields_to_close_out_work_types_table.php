<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCloseOutWorkTypesTable extends Migration
{
    public function up()
    {
        Schema::table('close_out_work_types', function (Blueprint $table) {
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_7907086')->references('id')->on('construction_contracts');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_7907090')->references('id')->on('teams');
        });
    }
}
