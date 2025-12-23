<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCloseOutDrivesTable extends Migration
{
    public function up()
    {
        Schema::table('close_out_drives', function (Blueprint $table) {
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_10776251')->references('id')->on('teams');
        });
    }
}
