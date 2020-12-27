<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganizationToSrtInputDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('srt_input_documents', function (Blueprint $table) {
            //
            $table->unsignedInteger('from_organization_id')->nullable();
            $table->foreign('from_organization_id', 'from_organization_fk_2871013')->references('id')->on('organizations');
        });
    }
}
