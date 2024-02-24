<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryMemberOwnedQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_member_owned_qualifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('temporary_member_career_id')->comment('仮会員経歴ID');
            $table->foreign('temporary_member_career_id','t_m_o_q_to_careers')->references('id')->on('temporary_member_careers')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedTinyInteger('owned_qualification')->comment('保有資格');
            $table->string('other_qualification')->nullable()->comment('その他保有資格');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temporary_member_owned_qualifications');
    }
}
