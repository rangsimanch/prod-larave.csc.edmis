<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToBoqItemsTable extends Migration
{
    public function up()
    {
        Schema::table('boq_items', function (Blueprint $table) {
            $table->unsignedInteger('boq_id')->nullable();
            $table->foreign('boq_id', 'boq_fk_1760116')->references('id')->on('bo_qs');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1760120')->references('id')->on('teams');
        });
    }
}
