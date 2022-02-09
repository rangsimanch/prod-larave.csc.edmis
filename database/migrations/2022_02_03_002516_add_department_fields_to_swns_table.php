<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentFieldsToSwnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('swns', function (Blueprint $table) {
            //
            $table->string('auditing_status')->nullable();
            $table->unsignedInteger('dept_code_id')->nullable();
            $table->foreign('dept_code_id', 'dept_code_fk_5906396')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('swns', function (Blueprint $table) {
            //
        });
    }
}
