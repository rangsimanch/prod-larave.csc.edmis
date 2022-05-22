<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstructionContractSrtExternalAgencyDocumentPivotTable extends Migration
{
    public function up()
    {
        Schema::create('construction_contract_srt_external_agency_document', function (Blueprint $table) {
            $table->unsignedInteger('srt_external_agency_document_id');
            $table->foreign('srt_external_agency_document_id', 'srt_external_agency_document_id_fk_6649339')->references('id')->on('srt_external_agency_documents')->onDelete('cascade');
            $table->unsignedInteger('construction_contract_id');
            $table->foreign('construction_contract_id', 'construction_contract_id_fk_6649339')->references('id')->on('construction_contracts')->onDelete('cascade');
        });
    }
}
