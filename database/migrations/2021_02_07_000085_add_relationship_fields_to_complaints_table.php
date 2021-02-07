<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToComplaintsTable extends Migration
{
    public function up()
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->unsignedInteger('construction_contract_id');
            $table->foreign('construction_contract_id', 'construction_contract_fk_3125714')->references('id')->on('construction_contracts');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_3125718')->references('id')->on('teams');
            $table->unsignedInteger('complaint_recipient_id');
            $table->foreign('complaint_recipient_id', 'complaint_recipient_fk_3125954')->references('id')->on('teams');
            $table->unsignedInteger('operator_id')->nullable();
            $table->foreign('operator_id', 'operator_fk_3125964')->references('id')->on('users');
        });
    }
}
