<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('status')->comment('ステータス');
            $table->string('billing_number')->unique()->comment('請求番号');
            $table->unsignedBigInteger('member_id')->comment('本会員ID');
            $table->foreign('member_id')->references('id')->on('members')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('subtotal')->comment('小計');
            $table->integer('tax')->comment('消費税');
            $table->integer('total')->comment('合計');
            $table->date('billing_month')->comment('請求対象年月');
            $table->timestamp('settlement_date')->nullable()->comment('決済実行日');
            $table->text('message')->nullable()->comment('エラーメッセージ');
            $table->text('member_note')->nullable()->comment('会員表示用備考');
            $table->text('user_note')->nullable()->comment('管理者用備考');
            $table->unsignedTinyInteger('is_not_billing')->comment('請求除外フラグ');
            $table->date('applied_at')->nullable()->comment('消し込み日');
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
        Schema::dropIfExists('billings', function (Blueprint $table) {
            $table->dropForeign('members_id_foreign');
        });
    }
}
