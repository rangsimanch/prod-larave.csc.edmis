<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameRfiToAuthorizedSignatoriesTable extends Migration
{
    public function up()
    {
        Schema::table('authorized_signatories', function (Blueprint $table) {
            $table->string('name_rfi')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('authorized_signatories', function (Blueprint $table) {
            $table->dropColumn('name_rfi');
        });
    }
}
