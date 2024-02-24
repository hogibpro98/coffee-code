<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryMemberCareerHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_member_career_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('temporary_member_career_id')->comment('仮会員経歴ID');
            $table->foreign('temporary_member_career_id','t_m_c_h_to_careers')->references('id')->on('temporary_member_careers')->onUpdate('cascade')->onDelete('cascade');
            $table->date('find_work')->comment('就職年月');
            $table->date('retirement')->nullable()->comment('退職年月');
            $table->string('office_name',255)->comment('会社名');
            $table->unsignedTinyInteger('status')->comment('ステータス');
            $table->text('free_entry')->nullable()->comment('職務詳細');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temporary_member_career_histories', function (Blueprint $table) {
            $table->dropForeign('temporary_member_careers_id_foreign');
        });
    }
}
