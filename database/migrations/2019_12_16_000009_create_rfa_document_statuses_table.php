<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfaDocumentStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('rfa_document_statuses', function (Blueprint $table) {
            $table->increments('id');

            $table->string('status_name')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
