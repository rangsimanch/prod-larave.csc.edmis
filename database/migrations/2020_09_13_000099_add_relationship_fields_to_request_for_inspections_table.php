<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToRequestForInspectionsTable extends Migration
{
    public function up()
    {
        Schema::table('request_for_inspections', function (Blueprint $table) {
            $table->unsignedInteger('contact_person_id')->nullable();
            $table->foreign('contact_person_id', 'contact_person_fk_1362233')->references('id')->on('users');
            $table->unsignedInteger('requested_by_id')->nullable();
            $table->foreign('requested_by_id', 'requested_by_fk_1362238')->references('id')->on('users');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1362245')->references('id')->on('teams');
            $table->unsignedInteger('construction_contract_id');
            $table->foreign('construction_contract_id', 'construction_contract_fk_1362246')->references('id')->on('construction_contracts');
            $table->unsignedInteger('bill_id')->nullable();
            $table->foreign('bill_id', 'bill_fk_1722432')->references('id')->on('bo_qs');
            $table->unsignedInteger('wbs_level_1_id')->nullable();
            $table->foreign('wbs_level_1_id', 'wbs_level_1_fk_1722433')->references('id')->on('wbs_level_ones');
            $table->unsignedInteger('wbs_level_3_id')->nullable();
            $table->foreign('wbs_level_3_id', 'wbs_level_3_fk_1722435')->references('id')->on('wbs_level_threes');
            $table->unsignedInteger('item_1_id')->nullable();
            $table->foreign('item_1_id', 'item_1_fk_2170823')->references('id')->on('boq_items');
            $table->unsignedInteger('item_2_id')->nullable();
            $table->foreign('item_2_id', 'item_2_fk_2170824')->references('id')->on('boq_items');
            $table->unsignedInteger('item_3_id')->nullable();
            $table->foreign('item_3_id', 'item_3_fk_2170825')->references('id')->on('boq_items');
        });
    }
}
