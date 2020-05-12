<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentDocumentDocumentTagPivotTable extends Migration
{
    public function up()
    {
        Schema::create('department_document_document_tag', function (Blueprint $table) {
            $table->unsignedInteger('department_document_id');
            $table->foreign('department_document_id', 'department_document_id_fk_1391453')->references('id')->on('department_documents')->onDelete('cascade');
            $table->unsignedInteger('document_tag_id');
            $table->foreign('document_tag_id', 'document_tag_id_fk_1391453')->references('id')->on('document_tags')->onDelete('cascade');
        });
    }
}