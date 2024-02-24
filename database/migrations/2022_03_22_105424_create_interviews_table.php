<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('temporary_member_id')->comment('仮会員ID');
            // 仮会員のデータが変更された場合は追従し、削除された場合はこちらも削除したいためcascadeを設定↓
            $table->foreign('temporary_member_id')->references('id')->on('temporary_members')->onUpdate('cascade')->onDelete('cascade');
            $table->longText('interview_candidates_time')->comment('面談候補日時');
            $table->timestamp('interview_fixed_time')->nullable()->comment('面談確定日時');
            $table->text('insertion_text_to_mail_template')->nullable()->comment('メールテンプレートへの差し込み本文');
            $table->timestamp('email_send_time')->nullable()->comment('メール送信日時');
            $table->text('note')->nullable()->comment('備考');
            $table->unsignedTinyInteger('status')->default(1)->comment('ステータス');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interviews', function (Blueprint $table) {

            // temporary_membersテーブルのデータが削除された場合一緒に削除する
            $table->dropForeign('temporary_members_id_foreign');
        });
    }

}
