<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAddLettersTable extends Migration
{
    public function up()
    {
        Schema::table('add_letters', function (Blueprint $table) {
            $table->unsignedInteger('sender_id');
            $table->foreign('sender_id', 'sender_fk_1556236')->references('id')->on('teams');
            $table->unsignedInteger('receiver_id');
            $table->foreign('receiver_id', 'receiver_fk_1556238')->references('id')->on('teams');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1556296')->references('id')->on('teams');
            $table->unsignedInteger('construction_contract_id');
            $table->foreign('construction_contract_id', 'construction_contract_fk_1556310')->references('id')->on('construction_contracts');
            $table->unsignedInteger('create_by_id')->nullable();
            $table->foreign('create_by_id', 'create_by_fk_2828770')->references('id')->on('users');
            $table->unsignedInteger('receive_by_id')->nullable();
            $table->foreign('receive_by_id', 'receive_by_fk_2828771')->references('id')->on('users');
        });
    }
}
