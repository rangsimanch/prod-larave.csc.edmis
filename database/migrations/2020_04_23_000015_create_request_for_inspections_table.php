<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestForInspectionsTable extends Migration
{
    public function up()
    {
        Schema::create('request_for_inspections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bill_no')->nullable();
            $table->string('subject')->nullable();
            $table->string('item_no')->nullable();
            $table->string('ref_no')->nullable();
            $table->datetime('inspection_date_time')->nullable();
            $table->string('type_of_work')->nullable();
            $table->string('location')->nullable();
            $table->longText('details_of_inspection')->nullable();
            $table->string('ref_specification')->nullable();
            $table->string('result_of_inspection')->nullable();
            $table->longText('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }
}
