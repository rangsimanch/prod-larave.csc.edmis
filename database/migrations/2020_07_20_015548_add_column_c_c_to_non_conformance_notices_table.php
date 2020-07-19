<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCCToNonConformanceNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('non_conformance_notices', function (Blueprint $table) {
            //
            $table->boolean('cc_srt')->default(0)->nullable();
            $table->boolean('cc_pmc')->default(0)->nullable();
            $table->boolean('cc_cec')->default(0)->nullable();
            $table->boolean('cc_csc')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('non_conformance_notices', function (Blueprint $table) {
            //
        });
    }
}
