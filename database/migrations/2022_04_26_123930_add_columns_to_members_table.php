<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->timestamp('advisory_updated_at')->after('advisory_experience_years')->default(date('y-m-d H:i:s'))->comment('アドバイザリー経験年数最終更新日時');
            $table->timestamp('field_updated_at')->after('advisory_updated_at')->default(date('y-m-d H:i:s'))->comment('専門分野最終更新日時');
            $table->timestamp('experience_updated_at')->after('experience')->default(date('y-m-d H:i:s'))->comment('要約最終更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('advisory_updated_at');
            $table->dropColumn('field_updated_at');
            $table->dropColumn('experience_updated_at');
        });
    }
}
