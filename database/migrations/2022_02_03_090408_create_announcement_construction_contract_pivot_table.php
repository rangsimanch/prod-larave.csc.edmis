<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementConstructionContractPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcement_construction_contract', function (Blueprint $table) {
            $table->unsignedInteger('announcement_id');
            $table->foreign('announcement_id', 'announcement_id_fk_4598684')->references('id')->on('announcements')->onDelete('cascade');
            $table->unsignedInteger('construction_contract_id');
            $table->foreign('construction_contract_id', 'construction_contract_id_fk_4598684')->references('id')->on('construction_contracts')->onDelete('cascade');
        });
    }
}
