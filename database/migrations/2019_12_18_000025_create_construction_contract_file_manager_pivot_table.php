<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstructionContractFileManagerPivotTable extends Migration
{
    public function up()
    {
        Schema::create('construction_contract_file_manager', function (Blueprint $table) {
            $table->unsignedInteger('file_manager_id');

            $table->foreign('file_manager_id', 'file_manager_id_fk_741344')->references('id')->on('file_managers')->onDelete('cascade');

            $table->unsignedInteger('construction_contract_id');

            $table->foreign('construction_contract_id', 'construction_contract_id_fk_741344')->references('id')->on('construction_contracts')->onDelete('cascade');
        });
    }
}
