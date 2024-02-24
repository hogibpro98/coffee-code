<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToTemporaryMemberFieldTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temporary_member_field_types', function (Blueprint $table) {
            $table->unsignedTinyInteger('type')->after('field_id')->comment('種別');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temporary_member_field_types', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
