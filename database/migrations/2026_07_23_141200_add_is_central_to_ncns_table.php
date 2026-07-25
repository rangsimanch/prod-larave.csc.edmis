<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCentralToNcnsTable extends Migration
{
    public function up()
    {
        Schema::table('ncns', function (Blueprint $table) {
            $table->boolean('is_central')->default(false)->after('document_number');
        });
    }

    public function down()
    {
        Schema::table('ncns', function (Blueprint $table) {
            $table->dropColumn('is_central');
        });
    }
}
