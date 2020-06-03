<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCheckSheetsTable extends Migration
{
    public function up()
    {
        Schema::table('check_sheets', function (Blueprint $table) {
            $table->unsignedInteger('name_of_inspector_id')->nullable();
            $table->foreign('name_of_inspector_id', 'name_of_inspector_fk_1564275')->references('id')->on('users');
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_1564277')->references('id')->on('construction_contracts');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1564283')->references('id')->on('teams');
            $table->unsignedInteger('work_type_id')->nullable();
            $table->foreign('work_type_id', 'work_type_fk_1565856')->references('id')->on('download_system_works');
        });
    }
}
