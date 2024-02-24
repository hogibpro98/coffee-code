<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('gmo_transactions');
        Schema::create('stripe_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('billing_id')->comment('請求ID');
            $table->foreign('billing_id')->references('id')->on('billings')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('is_succeed')->default(false)->comment('成功フラグ');
            $table->string('card_id', 100)->comment('StripeカードID');
            $table->longText('response')->nullable()->comment('レスポンス');
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
        Schema::dropIfExists('stripe_transactions');
    }
}
