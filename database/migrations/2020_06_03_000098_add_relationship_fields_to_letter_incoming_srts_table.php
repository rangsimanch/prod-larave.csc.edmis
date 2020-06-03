<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToLetterIncomingSrtsTable extends Migration
{
    public function up()
    {
        Schema::table('letter_incoming_srts', function (Blueprint $table) {
            $table->unsignedInteger('letter_id')->nullable();
            $table->foreign('letter_id', 'letter_fk_1556305')->references('id')->on('add_letters');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1556309')->references('id')->on('teams');
        });
    }
}
