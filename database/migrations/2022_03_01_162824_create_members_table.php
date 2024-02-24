<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_number',10)->unique()->index()->comment('会員番号');
            $table->string('name_kanji',100)->comment('氏名（漢字）');
            $table->string('name_furigana',100)->comment('氏名（フリガナ）');
            $table->string('email',255)->comment('メールアドレス');
            $table->timestamp('email_verified_at')->nullable()->comment('メールアドレス確認');
            $table->string('remember_token',100)->nullable()->comment('メールアドレス');
            $table->string('password',255)->comment('パスワード');
            $table->date('birthdate')->comment('生年月日');
            $table->unsignedTinyInteger('gender')->nullable()->comment('性別');
            $table->string('office_name',255)->comment('事業所名');
            $table->string('postal_code',20)->comment('郵便番号');
            $table->unsignedTinyInteger('prefecture')->comment('都道府県');
            $table->string('address1',255)->comment('市区町村');
            $table->string('address2',255)->comment('番地以下');
            $table->string('tel1',10)->comment('市外局番');
            $table->string('tel2',10)->comment('市内局番');
            $table->string('tel3',10)->comment('加入者番号');
            $table->longText('owned_qualifications')->nullable()->comment('保有資格');
            $table->string('certified_accountant_number',10)->nullable()->comment('公認会計士登録番号');
            $table->string('us_certified_accountant_number',10)->nullable()->comment('米国公認会計士登録番号');
            $table->string('tax_accountant_number',10)->nullable()->comment('税理士登録番号');
            $table->unsignedTinyInteger('advisory_experience_years')->comment('アドバイザリー経験年数');
            $table->text('other_specialized_field')->nullable()->comment('その他専門分野');
            $table->text('experience')->comment('要約');
            $table->text('note')->nullable()->comment('備考');
            $table->boolean('is_partner')->comment('Partnerフラグ');
            $table->boolean('is_release_working_status')->comment('稼働状況公開フラグ');
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
        Schema::dropIfExists('members');
    }
}
