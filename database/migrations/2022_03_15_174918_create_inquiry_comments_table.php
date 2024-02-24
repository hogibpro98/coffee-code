<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiryCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inquiry_id')->comment('意見箱ID');
            $table->foreign('inquiry_id')->references('id')->on('inquiries')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('member_id')->nullable()->comment('本会員ID');
            $table->unsignedBigInteger('user_id')->nullable()->comment('ユーザID');
            $table->text('content')->nullable()->comment('コメント本文');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiry_comments', function (Blueprint $table) {
            $table->dropForeign('inquiries_id_foreign');
        });
    }
}
