<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemarksForMailToEventSeminarDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_seminar_dates', function (Blueprint $table) {
            $table->text('remarks_for_manager')->nullable()->comment('管理者用備考');
            $table->text('remarks_for_mail')->nullable()->comment('メール用備考');
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
            $table->dropColumn('remarks_for_mail');
            $table->dropColumn('remarks_for_manager');
        });
    }
}
