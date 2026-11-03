<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAuthorizedSignatoriesTable extends Migration
{
    public function up()
    {
        Schema::table('authorized_signatories', function (Blueprint $table) {
            $table->unsignedInteger('construction_contract_id')->after('id');
            $table->foreign('construction_contract_id', 'construction_contract_fk_as')->references('id')->on('construction_contracts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('authorized_signatories', function (Blueprint $table) {
            $table->dropForeign('construction_contract_fk_as');
            $table->dropColumn('construction_contract_id');
        });
    }
}
