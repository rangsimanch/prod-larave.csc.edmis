<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsAndAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('documents_and_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name')->nullable();
            $table->string('original_no')->nullable();
            $table->string('receipt_no')->nullable();
            $table->date('date_of_receipt')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
