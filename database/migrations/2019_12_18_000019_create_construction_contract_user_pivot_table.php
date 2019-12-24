<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstructionContractUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('construction_contract_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id');

            $table->foreign('user_id', 'user_id_fk_746751')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('construction_contract_id');

            $table->foreign('construction_contract_id', 'construction_contract_id_fk_746751')->references('id')->on('construction_contracts')->onDelete('cascade');
        });
    }
}
