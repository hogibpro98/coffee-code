<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveInterviewCandidatesTimeFromInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interviews', function (Blueprint $table) {
            // 面談候補日時はメールテンプレートへの差し込み本文に入れる仕様になったためカラムを削除
            $table->dropColumn('interview_candidates_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->longText('interview_candidates_time')->comment('面談候補日時');
        });
    }
}
