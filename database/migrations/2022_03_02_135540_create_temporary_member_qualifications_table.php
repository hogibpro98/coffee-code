<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryMemberQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_member_qualifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('temporary_member_id')->comment('仮会員ID');
            $table->foreign('temporary_member_id')->references('id')->on('temporary_members')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedTinyInteger('qualification')->comment('入会資格');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temporary_member_qualifications', function (Blueprint $table) {
            $table->dropForeign('temporary_members_id_foreign');
        });
    }
}
