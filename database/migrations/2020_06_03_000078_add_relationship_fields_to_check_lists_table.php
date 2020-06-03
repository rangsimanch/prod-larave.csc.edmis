<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCheckListsTable extends Migration
{
    public function up()
    {
        Schema::table('check_lists', function (Blueprint $table) {
            $table->unsignedInteger('work_type_id')->nullable();
            $table->foreign('work_type_id', 'work_type_fk_1564953')->references('id')->on('task_tags');
            $table->unsignedInteger('name_of_inspector_id')->nullable();
            $table->foreign('name_of_inspector_id', 'name_of_inspector_fk_1564957')->references('id')->on('users');
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_1564959')->references('id')->on('construction_contracts');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1564965')->references('id')->on('teams');
        });
    }
}
