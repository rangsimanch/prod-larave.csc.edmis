<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCentralToSwnsTable extends Migration
{
    public function up()
    {
        Schema::table('swns', function (Blueprint $table) {
            $table->boolean('is_central')->default(false)->after('document_number');
        });
    }

    public function down()
    {
        Schema::table('swns', function (Blueprint $table) {
            $table->dropColumn('is_central');
        });
    }
}
