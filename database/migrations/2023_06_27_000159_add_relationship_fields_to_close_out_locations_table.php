<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCloseOutLocationsTable extends Migration
{
    public function up()
    {
        Schema::table('close_out_locations', function (Blueprint $table) {
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_7907019')->references('id')->on('construction_contracts');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_7907023')->references('id')->on('teams');
        });
    }
}
