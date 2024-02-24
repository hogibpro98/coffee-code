<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('billing_id')->comment('請求ID');
            $table->foreign('billing_id')->references('id')->on('billings')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name',255)->comment('費目名');
            $table->text('note')->comment('備考');
            $table->bigInteger('price')->comment('金額');
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
        Schema::dropIfExists('billing_details', function (Blueprint $table) {
            $table->dropForeign('billings_id_foreign');
        });
    }
}
