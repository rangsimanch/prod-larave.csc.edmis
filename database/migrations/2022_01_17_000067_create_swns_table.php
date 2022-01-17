<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwnsTable extends Migration
{
    public function up()
    {
        Schema::create('swns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('document_number');
            $table->date('submit_date')->nullable();
            $table->string('location')->nullable();
            $table->string('reply_ncr')->nullable();
            $table->longText('ref_doc')->nullable();
            $table->longText('description')->nullable();
            $table->longText('root_case')->nullable();
            $table->longText('containment_action')->nullable();
            $table->longText('corrective')->nullable();
            $table->string('review_status')->nullable();
            $table->string('documents_status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
