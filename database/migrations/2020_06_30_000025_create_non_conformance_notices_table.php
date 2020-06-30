<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNonConformanceNoticesTable extends Migration
{
    public function up()
    {
        Schema::create('non_conformance_notices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject')->nullable();
            $table->longText('description')->nullable();
            $table->string('ref_no')->nullable();
            $table->date('submit_date')->nullable();
            $table->longText('attachment_description')->nullable();
            $table->string('status_ncn')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
