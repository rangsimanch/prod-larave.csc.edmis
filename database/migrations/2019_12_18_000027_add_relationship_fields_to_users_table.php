<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('team_id')->nullable();

            $table->foreign('team_id', 'team_fk_578692')->references('id')->on('teams');

            $table->unsignedInteger('jobtitle_id')->nullable();

            $table->foreign('jobtitle_id', 'jobtitle_fk_622295')->references('id')->on('jobtitles');
        });
    }
}
