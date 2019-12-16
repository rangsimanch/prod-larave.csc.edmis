<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToFileManagersTable extends Migration
{
    public function up()
    {
        Schema::table('file_managers', function (Blueprint $table) {
            $table->unsignedInteger('team_id')->nullable();

            $table->foreign('team_id', 'team_fk_726009')->references('id')->on('teams');

            $table->unsignedInteger('construction_contract_id')->nullable();

            $table->foreign('construction_contract_id', 'construction_contract_fk_739595')->references('id')->on('construction_contracts');
        });
    }
}
