<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAddDrawingsTable extends Migration
{
    public function up()
    {
        Schema::table('add_drawings', function (Blueprint $table) {
            $table->unsignedInteger('activity_type_id')->nullable();
            $table->foreign('activity_type_id', 'activity_type_fk_1565923')->references('id')->on('download_system_activities');
            $table->unsignedInteger('work_type_id')->nullable();
            $table->foreign('work_type_id', 'work_type_fk_1565924')->references('id')->on('download_system_works');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1565931')->references('id')->on('teams');
        });
    }
}
