<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocumentsAndAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::table('documents_and_assignments', function (Blueprint $table) {
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_1602800')->references('id')->on('construction_contracts');
        });
    }
}
