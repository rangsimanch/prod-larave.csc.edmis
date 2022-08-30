<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecoveryFilesTable extends Migration
{
    public function up()
    {
        Schema::create('recovery_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('recovery_success')->nullable();
            $table->longText('recovery_fail')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
