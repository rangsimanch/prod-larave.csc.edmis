<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddLetterTeamPivotTable extends Migration
{
    public function up()
    {
        Schema::create('add_letter_team', function (Blueprint $table) {
            $table->unsignedInteger('add_letter_id');
            $table->foreign('add_letter_id', 'add_letter_id_fk_2822294')->references('id')->on('add_letters')->onDelete('cascade');
            $table->unsignedInteger('team_id');
            $table->foreign('team_id', 'team_id_fk_2822294')->references('id')->on('teams')->onDelete('cascade');
        });
    }
}
