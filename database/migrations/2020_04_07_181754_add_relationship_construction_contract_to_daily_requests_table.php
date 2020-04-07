<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipConstructionContractToDailyRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_requests', function (Blueprint $table) {
            //
            $table->unsignedInteger('constuction_contract_id')->nullable();
            $table->foreign('constuction_contract_id', 'constuction_contract_fk_1270794')->references('id')->on('construction_contracts');
        });
    }

}
