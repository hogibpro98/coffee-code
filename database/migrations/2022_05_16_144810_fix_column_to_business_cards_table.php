<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixColumnToBusinessCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_cards', function (Blueprint $table) {
            $table->dropColumn('card_name_furigana');
            $table->dropColumn('card_postal_code');
            $table->dropColumn('card_prefecture');
            $table->dropColumn('card_address1');
            $table->dropColumn('card_address2');
            $table->dropColumn('card_tel1');
            $table->dropColumn('card_tel2');
            $table->dropColumn('card_tel3');
            $table->string('card_name_roman',100)->after('card_name_kanji')->comment('名刺記載氏名（ローマ字）');
            $table->boolean('is_describe_office_name')->after('card_office_name')->default(true)->comment('事業所名記載フラグ');
            $table->json('card_qualification')->after('is_describe_office_name')->nullable()->comment('名刺記載資格');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_cards', function (Blueprint $table) {
            $table->dropColumn('card_qualification');
            $table->dropColumn('is_describe_office_name');
            $table->dropColumn('card_name_roman');
            $table->string('card_tel3',10)->after('card_office_name')->comment('名刺記載加入者番号');
            $table->string('card_tel2',10)->after('card_office_name')->comment('名刺記載市内局番');
            $table->string('card_tel1',10)->after('card_office_name')->comment('名刺記載市外局番');
            $table->string('card_address2',255)->after('card_office_name')->comment('名刺記載番地以下');
            $table->string('card_address1',255)->after('card_office_name')->comment('名刺記載市区町村');
            $table->unsignedTinyInteger('card_prefecture')->after('card_office_name')->comment('名刺記載都道府県');
            $table->string('card_postal_code',20)->after('card_office_name')->comment('名刺記載郵便番号');
            $table->string('card_name_furigana',100)->after('card_name_kanji')->comment('名刺記載氏名（フリガナ）');
        });
    }
}
