<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNonConformanceReportsTable extends Migration
{
    public function up()
    {
        Schema::create('non_conformance_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('root_cause')->nullable();
            $table->longText('corrective_action')->nullable();
            $table->longText('preventive_action')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('csc_consideration_status')->nullable();
            $table->string('csc_disposition_status')->nullable();
            $table->date('response_date')->nullable();
            $table->string('corresponding_to')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
