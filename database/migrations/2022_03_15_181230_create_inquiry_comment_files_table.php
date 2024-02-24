<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiryCommentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry_comment_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inquiry_comment_id')->comment('意見箱コメントID');
            $table->foreign('inquiry_comment_id')->references('id')->on('inquiry_comments')->onUpdate('cascade')->onDelete('cascade');
            $table->string('file_name',255)->comment('ファイル名');
            $table->string('file_path',1024)->comment('ファイルパス');
            $table->string('mime_type',100)->comment('mime_type');
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
        Schema::dropIfExists('inquiry_comment_files', function (Blueprint $table) {
            $table->dropForeign('inquiry_comments_id_foreign');
        });
    }
}
