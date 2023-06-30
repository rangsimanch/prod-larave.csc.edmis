<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCloseOutInspectionsTable extends Migration
{
    public function up()
    {
        Schema::table('close_out_inspections', function (Blueprint $table) {
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_7907314')->references('id')->on('construction_contracts');
            $table->unsignedInteger('reviewer_id')->nullable();
            $table->foreign('reviewer_id', 'reviewer_fk_7907328')->references('id')->on('users');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_7907334')->references('id')->on('teams');
        });
    }
}
