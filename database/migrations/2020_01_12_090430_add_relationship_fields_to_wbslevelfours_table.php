<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToWbslevelfoursTable extends Migration
{
    public function up()
    {
        Schema::table('wbslevelfours', function (Blueprint $table) {
            $table->unsignedInteger('boq_id')->nullable();

            $table->foreign('boq_id', 'boq_fk_859440')->references('id')->on('bo_qs');
        });
    }
}
