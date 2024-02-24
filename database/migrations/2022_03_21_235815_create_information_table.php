<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->string('title',255)->comment('タイトル');
            $table->text('content')->comment('内容');
            $table->date('display_start_date')->nullable()->comment('表示開始日');
            $table->date('display_end_date')->nullable()->comment('表示終了日');
            $table->unsignedTinyInteger('is_private')->default(1)->comment('非公開フラグ');
            $table->unsignedTinyInteger('status')->comment('ステータス');
            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('information');
    }
}
