<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToRfasTable extends Migration
{
    public function up()
    {
        Schema::table('rfas', function (Blueprint $table) {
            $table->unsignedInteger('issueby_id')->nullable();

            $table->foreign('issueby_id', 'issueby_fk_594337')->references('id')->on('users');

            $table->unsignedInteger('assign_id')->nullable();

            $table->foreign('assign_id', 'assign_fk_594338')->references('id')->on('users');

            $table->unsignedInteger('comment_by_id')->nullable();

            $table->foreign('comment_by_id', 'comment_by_fk_594343')->references('id')->on('users');

            $table->unsignedInteger('information_by_id')->nullable();

            $table->foreign('information_by_id', 'information_by_fk_594344')->references('id')->on('users');

            $table->unsignedInteger('type_id')->nullable();

            $table->foreign('type_id', 'type_fk_594370')->references('id')->on('rfatypes');

            $table->unsignedInteger('comment_status_id')->nullable();

            $table->foreign('comment_status_id', 'comment_status_fk_594371')->references('id')->on('rfa_comment_statuses');

            $table->unsignedInteger('for_status_id')->nullable();

            $table->foreign('for_status_id', 'for_status_fk_594372')->references('id')->on('rfa_comment_statuses');

            $table->unsignedInteger('document_status_id')->nullable();

            $table->foreign('document_status_id', 'document_status_fk_594378')->references('id')->on('rfa_document_statuses');

            $table->unsignedInteger('team_id')->nullable();

            $table->foreign('team_id', 'team_fk_673427')->references('id')->on('teams');

            $table->unsignedInteger('construction_contract_id')->nullable();

            $table->foreign('construction_contract_id', 'construction_contract_fk_746744')->references('id')->on('construction_contracts');

            $table->unsignedInteger('create_by_user_id')->nullable();

            $table->foreign('create_by_user_id', 'create_by_user_fk_746745')->references('id')->on('users');

            $table->unsignedInteger('update_by_user_id')->nullable();

            $table->foreign('update_by_user_id', 'update_by_user_fk_746746')->references('id')->on('users');

            $table->unsignedInteger('approve_by_user_id')->nullable();

            $table->foreign('approve_by_user_id', 'approve_by_user_fk_746747')->references('id')->on('users');
        });
    }
}
