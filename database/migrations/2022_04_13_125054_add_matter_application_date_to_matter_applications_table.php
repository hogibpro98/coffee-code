<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMatterApplicationDateToMatterApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matter_applications', function (Blueprint $table) {
            $table->date('matter_application_date')->comment('案件申込日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matter_applications', function (Blueprint $table) {
            $table->dropColumn('matter_application_date');
        });
    }
}
