<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstructionContractTaskPivotTable extends Migration
{
    public function up()
    {
        Schema::create('construction_contract_task', function (Blueprint $table) {
            $table->unsignedInteger('task_id');

            $table->foreign('task_id', 'task_id_fk_741287')->references('id')->on('tasks')->onDelete('cascade');

            $table->unsignedInteger('construction_contract_id');

            $table->foreign('construction_contract_id', 'construction_contract_id_fk_741287')->references('id')->on('construction_contracts')->onDelete('cascade');
        });
    }
}
