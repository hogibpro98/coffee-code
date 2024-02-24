<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatterApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matter_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->comment('本会員ID');
            $table->foreign('member_id')->references('id')->on('members')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('matter_id')->comment('案件ID');
            $table->foreign('matter_id')->references('id')->on('matters')->cascadeOnDelete()->cascadeOnDelete();
            $table->unsignedTinyInteger('status')->comment('ステータス');
            $table->timestamp('automatic_email_send_time')->nullable()->comment('自動メール送信日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('matter_applications', function (Blueprint $table) {
            $table->dropForeign('members_id_foreign');
            $table->dropForeign('matters_id_foreign');
        });
    }
}
