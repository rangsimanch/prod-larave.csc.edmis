<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDailyReportsTable extends Migration
{
    public function up()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->unsignedInteger('receive_by_id')->nullable();
            $table->foreign('receive_by_id', 'receive_by_fk_1167863')->references('id')->on('users');
        });

    }
}
