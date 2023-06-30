<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseOutPunchListsTable extends Migration
{
    public function up()
    {
        Schema::create('close_out_punch_lists', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('subject');
            $table->string('document_number')->nullable();
            $table->date('submit_date');
            $table->longText('sub_location')->nullable();
            $table->longText('sub_worktype')->nullable();
            $table->date('respond_date')->nullable();
            $table->string('review_status')->nullable();
            $table->date('review_date')->nullable();
            $table->string('document_status')->nullable();
            $table->string('revision_list')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
