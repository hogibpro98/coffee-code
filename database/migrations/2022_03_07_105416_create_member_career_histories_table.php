<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberCareerHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_career_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->comment('本会員ID');
            $table->foreign('member_id')->references('id')->on('members')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('member_career_histories', function (Blueprint $table) {
            $table->dropForeign('members_id_foreign');
        });
    }
}
