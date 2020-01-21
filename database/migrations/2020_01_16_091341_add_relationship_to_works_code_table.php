<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToWorksCodeTable extends Migration
{
    public function up()
    {
        Schema::table('construction_contracts', function (Blueprint $table) {
            $table->unsignedInteger('works_code_id')->nullable();

            $table->foreign('works_code_id', 'works_code_fk_877356')->references('id')->on('works_codes');
        });
    }
}
