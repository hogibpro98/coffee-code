<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTokenTemporaryMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temporary_members', function (Blueprint $table) {
            $table->string('token',100)->after('interview_status')->nullable()->unique()->comment('仮登録用トークン');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temporary_members', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
}
