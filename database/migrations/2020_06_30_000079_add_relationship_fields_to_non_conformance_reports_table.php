<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToNonConformanceReportsTable extends Migration
{
    public function up()
    {
        Schema::table('non_conformance_reports', function (Blueprint $table) {
            $table->unsignedInteger('ncn_ref_id')->nullable();
            $table->foreign('ncn_ref_id', 'ncn_ref_fk_1750658')->references('id')->on('non_conformance_notices');
            $table->unsignedInteger('prepared_by_id')->nullable();
            $table->foreign('prepared_by_id', 'prepared_by_fk_1750664')->references('id')->on('users');
            $table->unsignedInteger('contractors_project_id')->nullable();
            $table->foreign('contractors_project_id', 'contractors_project_fk_1750665')->references('id')->on('users');
            $table->unsignedInteger('approved_by_id')->nullable();
            $table->foreign('approved_by_id', 'approved_by_fk_1750667')->references('id')->on('users');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1750673')->references('id')->on('teams');
        });
    }
}
