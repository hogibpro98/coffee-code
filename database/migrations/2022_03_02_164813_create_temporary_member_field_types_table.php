<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryMemberFieldTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_member_field_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('temporary_member_career_id')->comment('仮会員経歴ID');
            $table->foreign('temporary_member_career_id','t_m_f_t_to_careers')->references('id')->on('temporary_member_careers')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('field_id')->comment('分野管理ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temporary_member_field_types', function (Blueprint $table) {
            $table->dropForeign('temporary_member_careers_id_foreign');
        });
    }
}
