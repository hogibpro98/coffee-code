<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSeminarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_seminars', function (Blueprint $table) {
            $table->id();
            $table->string('title',255)->comment('タイトル');
            $table->unsignedTinyInteger('type')->default(1)->comment('種別');
            $table->unsignedTinyInteger('is_private')->default(1)->comment('非公開フラグ');
            $table->text('content')->comment('内容');
            $table->date('application_start_date')->comment('申込開始日');
            $table->date('application_end_date')->comment('申込終了日');
            $table->unsignedTinyInteger('fee_type')->default(1)->comment('参加費タイプ');
            $table->unsignedInteger('fee')->nullable()->comment('参加費');
            $table->unsignedTinyInteger('capacity_type')->default(1)->comment('定員数タイプ');
            $table->unsignedTinyInteger('holding_type')->default(1)->comment('開催種別');
            $table->unsignedTinyInteger('holding_time_type')->comment('開催日時タイプ');
            $table->unsignedTinyInteger('status')->comment('ステータス');
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
        Schema::dropIfExists('event_seminars');
    }
}
