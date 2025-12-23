<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipSubjectFieldToCloseOutMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('close_out_mains', function (Blueprint $table) {
            $table->unsignedInteger('closeout_subject_id')->nullable();
            $table->foreign('closeout_subject_id', 'closeout_subject_fk_10776277')->references('id')->on('close_out_descriptions');
        });
    }
}
