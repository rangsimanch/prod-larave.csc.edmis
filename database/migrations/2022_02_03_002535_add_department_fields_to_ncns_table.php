<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentFieldsToNcnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ncns', function (Blueprint $table) {
            $table->unsignedInteger('dept_code_id')->nullable();
            $table->foreign('dept_code_id', 'dept_code_fk_5906464')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ncns', function (Blueprint $table) {
            //
        });
    }
}
