<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToRequestForInformationsTable extends Migration
{
    public function up()
    {
        Schema::table('request_for_informations', function (Blueprint $table) {
            $table->unsignedInteger('to_id')->nullable();
            $table->foreign('to_id', 'to_fk_1722499')->references('id')->on('teams');
            $table->unsignedInteger('request_by_id')->nullable();
            $table->foreign('request_by_id', 'request_by_fk_1722508')->references('id')->on('users');
            $table->unsignedInteger('authorised_rep_id')->nullable();
            $table->foreign('authorised_rep_id', 'authorised_rep_fk_1722512')->references('id')->on('users');
            $table->unsignedInteger('response_organization_id')->nullable();
            $table->foreign('response_organization_id', 'response_organization_fk_1722513')->references('id')->on('teams');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1722519')->references('id')->on('teams');
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_1735821')->references('id')->on('construction_contracts');
        });
    }
}
