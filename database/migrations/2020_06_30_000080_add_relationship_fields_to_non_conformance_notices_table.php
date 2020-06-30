<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToNonConformanceNoticesTable extends Migration
{
    public function up()
    {
        Schema::table('non_conformance_notices', function (Blueprint $table) {
            $table->unsignedInteger('csc_issuers_id')->nullable();
            $table->foreign('csc_issuers_id', 'csc_issuers_fk_1750594')->references('id')->on('users');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1750599')->references('id')->on('teams');
        });
    }
}
