<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status')->nullable();
            $table->string('document_number');
            $table->date('received_date');
            $table->string('source_code');
            $table->string('complainant')->nullable();
            $table->string('complainant_tel')->nullable();
            $table->string('complainant_detail')->nullable();
            $table->longText('complaint_description')->nullable();
            $table->string('type_code');
            $table->string('impact_code');
            $table->longText('action_detail')->nullable();
            $table->date('action_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
