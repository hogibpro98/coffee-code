<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_private')->comment('非公開フラグ');
            $table->string('title',255)->comment('タイトル');
            $table->text('content')->nullable()->comment('内容');
            $table->string('image_name',255)->nullable()->comment('バナー画像名');
            $table->text('image_path')->nullable()->comment('バナー画像パス');
            $table->text('url')->nullable()->comment('リンク先URL');
            $table->unsignedSmallInteger('display_order')->nullable()->comment('表示順');
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
        Schema::dropIfExists('links');
    }
}
