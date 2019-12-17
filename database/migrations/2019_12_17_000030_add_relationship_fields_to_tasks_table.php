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

            $table->unsignedInteger('user_create_id')->nullable();

            $table->foreign('user_create_id', 'user_create_fk_635622')->references('id')->on('users');
        });
    }
}
