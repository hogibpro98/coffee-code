<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInterviewStartTimeAndOtherToInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->date('interview_fixed_date')->after('temporary_member_id')->nullable()->comment('面談確定日');
            $table->time('interview_start_time')->after('interview_fixed_date')->nullable()->comment('面談開始時間');
            $table->time('interview_end_time')->after('interview_start_time')->nullable()->comment('面談終了時間');
            $table->dropColumn('interview_fixed_time');
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
            $table->timestamp('interview_fixed_time')->after('temporary_member_id')->nullable()->comment('面談確定日時');
            $table->dropColumn('interview_end_time');
            $table->dropColumn('interview_start_time');
            $table->dropColumn('interview_fixed_date');
        });
    }
}
