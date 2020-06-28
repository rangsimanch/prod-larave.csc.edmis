<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSiteWarningNoticesTable extends Migration
{
    public function up()
    {
        Schema::table('site_warning_notices', function (Blueprint $table) {
            $table->unsignedInteger('to_team_id')->nullable();
            $table->foreign('to_team_id', 'to_team_fk_1735884')->references('id')->on('teams');
            $table->unsignedInteger('construction_contract_id')->nullable();
            $table->foreign('construction_contract_id', 'construction_contract_fk_1735885')->references('id')->on('construction_contracts');
            $table->unsignedInteger('issue_by_id')->nullable();
            $table->foreign('issue_by_id', 'issue_by_fk_1735887')->references('id')->on('users');
            $table->unsignedInteger('reviewed_by_id')->nullable();
            $table->foreign('reviewed_by_id', 'reviewed_by_fk_1735888')->references('id')->on('users');
            $table->unsignedInteger('containment_responsible_id')->nullable();
            $table->foreign('containment_responsible_id', 'containment_responsible_fk_1735892')->references('id')->on('users');
            $table->unsignedInteger('corrective_responsible_id')->nullable();
            $table->foreign('corrective_responsible_id', 'corrective_responsible_fk_1735895')->references('id')->on('users');
            $table->unsignedInteger('section_2_reviewed_by_id')->nullable();
            $table->foreign('section_2_reviewed_by_id', 'section_2_reviewed_by_fk_1735897')->references('id')->on('users');
            $table->unsignedInteger('section_2_approved_by_id')->nullable();
            $table->foreign('section_2_approved_by_id', 'section_2_approved_by_fk_1735898')->references('id')->on('users');
            $table->unsignedInteger('csc_issuer_id')->nullable();
            $table->foreign('csc_issuer_id', 'csc_issuer_fk_1735901')->references('id')->on('users');
            $table->unsignedInteger('csc_qa_id')->nullable();
            $table->foreign('csc_qa_id', 'csc_qa_fk_1735902')->references('id')->on('users');
            $table->unsignedInteger('csc_pm_id')->nullable();
            $table->foreign('csc_pm_id', 'csc_pm_fk_1735904')->references('id')->on('users');
            $table->unsignedInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_1735908')->references('id')->on('teams');
        });
    }
}
