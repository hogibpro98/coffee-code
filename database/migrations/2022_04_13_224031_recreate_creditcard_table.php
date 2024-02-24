<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateCreditcardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('creditcards', function (Blueprint $table) {
            $table->dropForeign('members_id_foreign');
        });

        Schema::create('creditcards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->comment('本会員ID');
            $table->foreign('member_id')->references('id')->on('members')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('status')->comment('ステータス');
            $table->tinyInteger('is_constantly')->comment('通常使うカードフラグ');
            $table->string('stripe_card_id', 100)->comment('StripeカードID');
            $table->longText('stripe_data')->comment('Stripeデータ');
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
        //
    }
}
