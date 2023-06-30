<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseOutWorkTypesTable extends Migration
{
    public function up()
    {
        Schema::create('close_out_work_types', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('work_type_name');
            $table->string('work_type_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
