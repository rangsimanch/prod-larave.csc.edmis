<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAuthorizedSignatoryJobtitlesTable extends Migration
{
    public function up()
    {
        Schema::table('authorized_signatory_jobtitles', function (Blueprint $table) {
            $table->unsignedInteger('authorized_signatory_id')->after('id');
            $table->foreign('authorized_signatory_id', 'signatory_id_fk_asj')->references('id')->on('authorized_signatories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('authorized_signatory_jobtitles', function (Blueprint $table) {
            $table->dropForeign('signatory_id_fk_asj');
            $table->dropColumn('authorized_signatory_id');
        });
    }
}
