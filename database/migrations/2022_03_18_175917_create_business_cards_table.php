<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->comment('本会員ID');
            $table->foreign('member_id')->references('id')->on('members')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedTinyInteger('status')->comment('ステータス');
            $table->string('delivery_postal_code',20)->comment('配送先郵便番号');
            $table->unsignedTinyInteger('delivery_prefecture')->comment('配送先都道府県');
            $table->string('delivery_address1',255)->comment('配送先市区町村');
            $table->string('delivery_address2',255)->comment('配送先番地以下');
            $table->string('card_name_kanji',100)->comment('名刺記載氏名（漢字）');
            $table->string('card_name_furigana',100)->comment('名刺記載氏名（フリガナ）');
            $table->string('card_email',255)->comment('名刺記載メールアドレス');
            $table->string('card_office_name',255)->comment('名刺記載事業所名');
            $table->string('card_postal_code',20)->comment('名刺記載郵便番号');
            $table->unsignedTinyInteger('card_prefecture')->comment('名刺記載都道府県');
            $table->string('card_address1',255)->comment('名刺記載市区町村');
            $table->string('card_address2',255)->comment('名刺記載番地以下');
            $table->string('card_tel1',10)->comment('名刺記載市外局番');
            $table->string('card_tel2',10)->comment('名刺記載市内局番');
            $table->string('card_tel3',10)->comment('名刺記載加入者番号');
            $table->text('note')->comment('備考');
            $table->string('card_image',1024)->comment('名刺パス');
            $table->string('card_background_image',1024)->comment('名刺背景パス');
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
        Schema::dropIfExists('business_cards', function (Blueprint $table) {
            $table->dropForeign('members_id_foreign');
        });
    }
}
