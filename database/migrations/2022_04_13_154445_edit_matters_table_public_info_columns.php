<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditMattersTablePublicInfoColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matters', function (Blueprint $table) {
            $table->string('subject')->nullable()->change();
            $table->unsignedBigInteger('industry_type_id')->nullable()->change();
            $table->text('business_content')->nullable()->change();
            $table->date('application_start_date')->nullable()->change();
            $table->date('application_end_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matters', function (Blueprint $table) {
            $table->string('subject')->nullable(false)->change();
            $table->unsignedBigInteger('industry_type_id')->nullable(false)->change();
            $table->text('business_content')->nullable(false)->change();
            $table->date('application_start_date')->nullable(false)->change();
            $table->date('application_end_date')->nullable(false)->change();
        });
    }
}
