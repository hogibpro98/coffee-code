<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatterMemberAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matter_member_assigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->comment('本会員ID');
            $table->foreign('member_id')->references('id')->on('members')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedBigInteger('matter_id')->comment('案件ID');
            $table->foreign('matter_id')->references('id')->on('matters')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('matter_member_assigns', function (Blueprint $table) {
            $table->dropForeign('members_id_foreign');
            $table->dropForeign('matters_id_foreign');
        });
    }
}
