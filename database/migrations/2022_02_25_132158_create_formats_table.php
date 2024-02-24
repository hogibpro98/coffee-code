<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formats', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_private')->comment('非公開フラグ');
            $table->string('title',255)->comment('タイトル');
            $table->text('content')->nullable()->comment('内容');
            $table->longtext('tag')->nullable()->comment('タグ');
            $table->string('file_name',255)->nullable()->comment('ファイル名');
            $table->string('file_path',1024)->nullable()->comment('ファイルパス');
            $table->string('mime_type',100)->nullable()->comment('mime_type');
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
        Schema::dropIfExists('formats');
    }
}
