<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToMonthlyReportCscsTable extends Migration
{
    public function up()
    {
        Schema::table('monthly_report_cscs', function (Blueprint $table) {
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1566285')->references('id')->on('teams');
        });
    }
}
