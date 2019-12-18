<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTasksTable extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedInteger('status_id')->nullable();

            $table->foreign('status_id', 'status_fk_602592')->references('id')->on('task_statuses');

            $table->unsignedInteger('team_id')->nullable();

            $table->foreign('team_id', 'team_fk_631695')->references('id')->on('teams');

            $table->unsignedInteger('create_by_user_id')->nullable();

<<<<<<< HEAD:database/migrations/2019_12_17_000030_add_relationship_fields_to_tasks_table.php
            $table->foreign('user_create_id', 'user_create_fk_635622')->references('id')->on('users');
            

            $table->unsignedInteger('construction_contract_id')->nullable();

            $table->foreign('construction_contract_id', 'construction_contract_create_fk_675632')->references('id')->on('construction_contracts');

            
            
=======
            $table->foreign('create_by_user_id', 'create_by_user_fk_746748')->references('id')->on('users');

            $table->unsignedInteger('construction_contract_id')->nullable();

            $table->foreign('construction_contract_id', 'construction_contract_fk_746749')->references('id')->on('construction_contracts');
>>>>>>> 6583fa6204f7846f680bc6147fc560b6bd156dcf:database/migrations/2019_12_18_000028_add_relationship_fields_to_tasks_table.php
        });
    }
}
