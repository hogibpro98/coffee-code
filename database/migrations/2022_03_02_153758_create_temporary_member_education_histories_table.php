<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryMemberEducationHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_member_education_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('temporary_member_career_id')->comment('仮会員経歴ID');
            $table->foreign('temporary_member_career_id','t_m_e_h_to_careers')->references('id')->on('temporary_member_careers')->onUpdate('cascade')->onDelete('cascade');
            $table->date('admission')->comment('入学年月');
            $table->date('graduation')->comment('卒業年月');
            $table->string('school_name',255)->comment('学校名');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temporary_member_education_histories', function (Blueprint $table) {
                $table->dropForeign('temporary_member_careers_id_foreign');
            });
    }
}
