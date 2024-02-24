<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_reasons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->comment('本会員ID');
            $table->foreign('member_id')->references('id')->on('members')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('reason')->comment('退会理由');
            $table->text('detail')->nullable()->comment('退会理由詳細');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_reasons', function (Blueprint $table) {
            $table->dropForeign('members_id_foreign');
        });
    }
}
