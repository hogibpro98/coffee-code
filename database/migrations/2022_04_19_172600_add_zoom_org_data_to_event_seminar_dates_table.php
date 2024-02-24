<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZoomOrgDataToEventSeminarDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_seminar_dates', function (Blueprint $table) {
            $table->longText('zoom_org_data')->nullable()->comment('ZOOM情報');
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
            $table->dropColumn('zoom_org_data');
        });
    }
}
