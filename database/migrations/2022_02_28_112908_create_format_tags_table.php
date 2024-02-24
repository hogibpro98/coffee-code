<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormatTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('format_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('format_id')->comment('フォーマットID');
            $table->foreign('format_id')->references('id')->on('formats')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name',255)->comment('タグ名');
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
        Schema::dropIfExists('format_tags', function (Blueprint $table) {
            $table->dropForeign('formats_id_foreign');
        });
    }
}
