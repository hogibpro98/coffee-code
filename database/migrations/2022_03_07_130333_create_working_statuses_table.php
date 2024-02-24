<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->comment('本会員ID');
            $table->foreign('member_id')->references('id')->on('members')->onUpdate('cascade')->onDelete('cascade');
            $table->date('month')->comment('対象年月');
            $table->tinyInteger('week_1')->comment('一週目');
            $table->tinyInteger('week_2')->comment('ニ週目');
            $table->tinyInteger('week_3')->comment('三週目');
            $table->tinyInteger('week_4')->comment('四週目');
            $table->tinyInteger('week_5')->nullable()->comment('五週目');
            $table->tinyInteger('week_6')->nullable()->comment('六週目');
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
        Schema::dropIfExists('working_statuses', function (Blueprint $table) {
            $table->dropForeign('members_id_foreign');
        });
    }
}
