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
        });
    }
}
