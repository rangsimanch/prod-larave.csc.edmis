<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDailyRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('daily_requests', function (Blueprint $table) {
            $table->unsignedInteger('receive_by_id')->nullable();
            $table->foreign('receive_by_id', 'receive_by_fk_1167853')->references('id')->on('users');
        });

    }
}
