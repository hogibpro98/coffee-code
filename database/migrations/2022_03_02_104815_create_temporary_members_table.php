<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_members', function (Blueprint $table) {
            $table->id();
            $table->string('member_number',10)->unique()->index()->comment('会員番号');
            $table->foreign('member_number')->references('member_number')->on('members');
            $table->string('name_kanji',100)->comment('氏名（漢字）');
            $table->string('name_furigana',100)->comment('氏名（フリガナ）');
            $table->string('email',255)->comment('メールアドレス');
            $table->string('password',255)->comment('パスワード');
            $table->unsignedTinyInteger('interview_status')->nullable()->comment('面談ステータス');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temporary_members', function (Blueprint $table) {
            $table->dropForeign('members_member_number_foreign');
        });
    }
}
