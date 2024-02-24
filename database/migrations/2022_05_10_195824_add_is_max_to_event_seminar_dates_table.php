<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsMaxToEventSeminarDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_seminar_dates', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_max')->default(0)->comment('満員フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_seminar_dates', function (Blueprint $table) {
            $table->dropColumn('is_max');
        });
    }
}
