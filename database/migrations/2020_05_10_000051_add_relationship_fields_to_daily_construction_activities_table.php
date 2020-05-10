<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDailyConstructionActivitiesTable extends Migration
{
    public function up()
    {
        Schema::table('daily_construction_activities', function (Blueprint $table) {
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_1451046')->references('id')->on('construction_contracts');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1451051')->references('id')->on('teams');
        });
    }
}
