<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNcnsTable extends Migration
{
    public function up()
    {
        Schema::create('ncns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('document_number');
            $table->date('issue_date')->nullable();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->longText('attachment_description')->nullable();
            $table->integer('pages_of_attachment')->nullable();
            $table->date('acceptance_date')->nullable();
            $table->string('documents_status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
