<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSeminarDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_seminar_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_seminar_id')->comment('イベント・セミナーID');
            $table->foreign('event_seminar_id')->references('id')->on('event_seminars')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedTinyInteger('times')->comment('回数');
            $table->datetime('start_time')->comment('開始日時');
            $table->datetime('end_time')->comment('終了日時');
            $table->string('postal_code', 10)->nullable()->comment('郵便番号');
            $table->unsignedTinyInteger('prefecture')->nullable()->comment('都道府県');
            $table->string('address1', 255)->nullable()->comment('市区町村');
            $table->string('address2', 255)->nullable()->comment('番地以下');
            $table->unsignedSmallInteger('capacity')->nullable()->comment('定員数');
            $table->string('zoom_url', 1024)->nullable()->comment('ZOOMURL');
            $table->string('zoom_meeting_id', 20)->nullable()->comment('ZOOMミーティングID');
            $table->string('zoom_password', 20)->nullable()->comment('ZOOMパスワード');
            $table->string('archive_url', 1024)->nullable()->comment('アーカイブURL');
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

        Schema::dropIfExists('event_seminar_dates', function (Blueprint $table) {
            $table->dropForeign('event_seminars_id_foreign');
        });
    }
}
