<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixColumnToWorkingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('working_statuses', function (Blueprint $table) {
            $table->dropColumn('month');
            $table->dropColumn('week_1');
            $table->dropColumn('week_2');
            $table->dropColumn('week_3');
            $table->dropColumn('week_4');
            $table->dropColumn('week_5');
            $table->dropColumn('week_6');
            $table->date('start_date')->comment('開始日');
            $table->unsignedTinyInteger('rate')->nullable()->default(0)->comment('稼働可能率');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('working_statuses', function (Blueprint $table) {
            $table->dropColumn('rate');
            $table->dropColumn('start_date');
            $table->tinyInteger('week_6')->nullable()->comment('六週目');
            $table->tinyInteger('week_5')->nullable()->comment('五週目');
            $table->tinyInteger('week_4')->comment('四週目');
            $table->tinyInteger('week_3')->comment('三週目');
            $table->tinyInteger('week_2')->comment('ニ週目');
            $table->tinyInteger('week_1')->comment('一週目');
            $table->date('month')->comment('対象年月');
        });
    }
}
