<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNcrsTable extends Migration
{
    public function up()
    {
        Schema::create('ncrs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('document_number')->nullable();
            $table->date('acceptance_date')->nullable();
            $table->longText('root_case')->nullable();
            $table->longText('containment_action')->nullable();
            $table->longText('corrective')->nullable();
            $table->longText('attachment_description')->nullable();
            $table->integer('pages_of_attachment')->nullable();
            $table->string('documents_status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
