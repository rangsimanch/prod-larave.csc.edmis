<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToRequestForInspectionsTable extends Migration
{
    public function up()
    {
        Schema::table('request_for_inspections', function (Blueprint $table) {
            $table->unsignedInteger('contact_person_id')->nullable();
            $table->foreign('contact_person_id', 'contact_person_fk_1362233')->references('id')->on('users');
            $table->unsignedInteger('requested_by_id')->nullable();
            $table->foreign('requested_by_id', 'requested_by_fk_1362238')->references('id')->on('users');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1362245')->references('id')->on('teams');
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_1362246')->references('id')->on('construction_contracts');
        });

    }
}
