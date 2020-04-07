<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnNameToDailyRequestTable extends Migration
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
            $table->renameColumn('constuction_contract_id', 'construction_contract_id');
        });
    }

}
