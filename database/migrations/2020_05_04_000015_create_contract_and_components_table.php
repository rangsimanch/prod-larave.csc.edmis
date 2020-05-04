<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractAndComponentsTable extends Migration
{
    public function up()
    {
        Schema::create('contract_and_components', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_name')->nullable();
            $table->string('document_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }
}
