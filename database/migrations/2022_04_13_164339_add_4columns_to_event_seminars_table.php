<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add4columnsToEventSeminarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_seminars', function (Blueprint $table) {
            $table->dateTime('published_date')->nullable()->comment('公開日時');
            $table->string('cpe_registration', 255)->nullable()->comment('CPE登録');
            $table->string('organizer', 255)->nullable()->comment('主催者');
            $table->longText('times_infomation')->nullable()->comment('回情報');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_seminars', function (Blueprint $table) {
            $table->dropColumn('times_infomation');
            $table->dropColumn('organizer');
            $table->dropColumn('cpe_registration');
            $table->dropColumn('published_date');
        });
    }
}
