<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailForUpdateToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('token_for_update_email',100)->after('email_verified_at')->nullable()->unique()->default(null)->comment('メールアドレス変更用トークン');
            $table->string('email_for_update',255)->after('email_verified_at')->nullable()->default(null)->comment('変更用の仮メールアドレス');
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
            $table->dropColumn('email_for_update');
            $table->dropColumn('token_for_update_email');
        });
    }
}
