<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSeminarApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_seminar_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->comment('本会員ID');
            $table->foreign('member_id')->references('id')->on('members')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('event_seminar_date_id')->comment('イベント・セミナー日時ID');
            $table->foreign('event_seminar_date_id')->references('id')->on('event_seminar_dates')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedTinyInteger('is_canceled')->default(0)->comment('キャンセル済みフラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('event_seminar_applications', function (Blueprint $table) {
            $table->dropForeign('members_id_foreign');
            $table->dropForeign('event_seminar_dates_id_foreign');
        });
    }
}
