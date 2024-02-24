<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMattersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matters', function (Blueprint $table) {
            $table->id();
            $table->string('subject', 255)->comment('件名');
            $table->unsignedBigInteger('industry_type_id')->comment('業種ID');
            // 業種のデータが変更された場合は追従し（cascade）、案件で使用されている業種のレコードは削除できないようにする（restrict）↓
            $table->foreign('industry_type_id')->references('id')->on('industry_types')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedTinyInteger('is_private')->default(1)->comment('非公開フラグ');
            $table->text('overview')->nullable()->comment('概要');
            $table->text('business_content')->comment('業務内容');
            $table->string('reward', 255)->nullable()->comment('報酬');
            $table->string('period', 255)->nullable()->comment('期間');
            $table->string('area', 255)->nullable()->comment('地域');
            $table->string('weekly_working_days', 255)->nullable()->comment('週の稼働日数');
            $table->string('target_company', 255)->nullable()->comment('対象会社');
            $table->string('sales_scale', 255)->nullable()->comment('売上規模');
            $table->string('work_style', 255)->nullable()->comment('働き方');
            $table->date('application_start_date')->comment('申込開始日');
            $table->date('application_end_date')->comment('申込終了日');
            $table->text('qualifications')->nullable()->comment('その他応募条件');
            $table->unsignedTinyInteger('publication_range')->default(1)->comment('公開範囲');
            $table->string('introduction_company_name', 255)->nullable()->comment('仲介・紹介企業名');
            $table->unsignedBigInteger('client_id')->comment('依頼企業ID');
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('restrict');
            $table->date('order_date')->nullable()->comment('依頼年月日');
            $table->string('project_name', 255)->nullable()->comment('プロジェクト名');
            $table->string('gross_fee', 255)->nullable()->comment('Grossフィー');
            $table->string('net_fee', 255)->nullable()->comment('ネットフィー');
            $table->unsignedTinyInteger('matter_status')->comment('案件ステータス');
            $table->unsignedTinyInteger('contract_status')->comment('契約ステータス');
            $table->string('press_release_url', 1024)->nullable()->comment('プレスリリースURL');
            $table->text('note')->nullable()->comment('備考');
            $table->unsignedTinyInteger('status')->comment('ステータス');
            $table->string('matter_billing_code', 100)->nullable()->comment('案件・請求コード');
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

        Schema::dropIfExists('matters', function (Blueprint $table) {
            $table->dropForeign('industry_types_id_foreign');
            $table->dropForeign('clients_id_foreign');
        });
    }
}
