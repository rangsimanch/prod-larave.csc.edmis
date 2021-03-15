<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToRequestForInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_for_informations', function (Blueprint $table) {
            $table->string('document_status')->nullable();
            $table->string('save_for')->nullable();
            $table->string('originator_code')->nullable();
            $table->unsignedInteger('wbs_level_4_id')->nullable();
            $table->foreign('wbs_level_4_id', 'wbs_level_4_fk_3439238')->references('id')->on('wbs_level_threes');
            $table->unsignedInteger('wbs_level_5_id')->nullable();
            $table->foreign('wbs_level_5_id', 'wbs_level_5_fk_3439239')->references('id')->on('wbslevelfours');
        });
    }
}
