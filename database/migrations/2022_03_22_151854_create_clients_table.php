<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('industry_type_id')->comment('業種ID');
            // ↓業種が更新された時はそれに従い（cascade）、企業に使われている業種IDと一致する業種のレコードは削除できないようにする（restrict）
            $table->foreign('industry_type_id')->references('id')->on('industry_types')->onUpdate('cascade')->onDelete('restrict');
            $table->string('client_name_fullwidth',255)->comment('社名（全角）');
            $table->string('client_name_katakana',255)->nullable()->comment('社名（カタカナ）');
            $table->string('client_name_english',255)->nullable()->comment('社名（英）');
            $table->string('postal_code',10)->comment('郵便番号');
            $table->unsignedTinyInteger('prefecture')->comment('都道府県');
            $table->string('address1',255)->comment('市区町村');
            $table->string('address2',255)->comment('番地以下');
            $table->text('note')->nullable()->comment('備考');
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
        Schema::dropIfExists('clients', function (Blueprint $table) {
            $table->dropForeign('industry_types_id_foreign');
        });
    }
}
