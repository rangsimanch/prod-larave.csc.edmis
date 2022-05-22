<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSrtExternalAgencyDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('srt_external_agency_documents', function (Blueprint $table) {
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_6649369')->references('id')->on('teams');
        });
    }
}
