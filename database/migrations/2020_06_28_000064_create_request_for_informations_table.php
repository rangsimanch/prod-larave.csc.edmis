<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestForInformationsTable extends Migration
{
    public function up()
    {
        Schema::create('request_for_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('to_organization')->nullable();
            $table->string('attention_name')->nullable();
            $table->string('document_no')->nullable();
            $table->date('date')->nullable();
            $table->string('title');
            $table->string('discipline')->nullable();
            $table->string('originator_name')->nullable();
            $table->string('cc_to')->nullable();
            $table->string('incoming_no')->nullable();
            $table->date('incoming_date')->nullable();
            $table->longText('description')->nullable();
            $table->longText('attachment_file_description')->nullable();
            $table->string('outgoing_no')->nullable();
            $table->date('outgoing_date')->nullable();
            $table->longText('response')->nullable();
            $table->string('response_date')->nullable();
            $table->longText('record')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
