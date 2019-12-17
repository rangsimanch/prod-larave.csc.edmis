<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstructionContractRfaPivotTable extends Migration
{
    public function up()
    {
        Schema::create('construction_contract_rfa', function (Blueprint $table) {
            $table->unsignedInteger('rfa_id');

            $table->foreign('rfa_id', 'rfa_id_fk_741261')->references('id')->on('rfas')->onDelete('cascade');

            $table->unsignedInteger('construction_contract_id');

            $table->foreign('construction_contract_id', 'construction_contract_id_fk_741261')->references('id')->on('construction_contracts')->onDelete('cascade');
        });
    }
}
