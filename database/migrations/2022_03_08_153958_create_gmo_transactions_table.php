<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmoTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmo_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('billing_id')->comment('請求ID');
            $table->foreign('billing_id')->references('id')->on('billings')->onUpdate('cascade')->onDelete('cascade');
            $table->smallInteger('status')->comment('ステータス');
            $table->string('message',255)->nullable()->comment('メッセージ');
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
        Schema::dropIfExists('gmo_transactions', function (Blueprint $table) {
            $table->dropForeign('billings_id_foreign');
        });
    }
}
