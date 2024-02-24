<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryMemberCareersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_member_careers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('temporary_member_id')->comment('仮会員ID');
            $table->foreign('temporary_member_id')->references('id')->on('temporary_members')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('temporary_member_careers', function (Blueprint $table) {
            $table->dropForeign('temporary_members_id_foreign');
        });
    }
}
