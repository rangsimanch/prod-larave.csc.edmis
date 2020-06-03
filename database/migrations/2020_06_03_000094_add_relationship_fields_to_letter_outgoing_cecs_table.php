<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToLetterOutgoingCecsTable extends Migration
{
    public function up()
    {
        Schema::table('letter_outgoing_cecs', function (Blueprint $table) {
            $table->unsignedInteger('letter_id')->nullable();
            $table->foreign('letter_id', 'letter_fk_1556342')->references('id')->on('add_letters');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1556346')->references('id')->on('teams');
        });
    }
}
