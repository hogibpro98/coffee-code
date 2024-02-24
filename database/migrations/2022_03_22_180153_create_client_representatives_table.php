<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientRepresentativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_representatives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->comment('企業ID');
            // ↓企業が更新された時はそれに従い（cascade）、企業が削除された場合は一緒に削除する（cascade）
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name',255)->nullable()->comment('名前');
            $table->string('email',255)->nullable()->comment('メールアドレス');
            $table->string('tel',20)->nullable()->comment('電話番号');
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
        Schema::dropIfExists('client_representatives', function (Blueprint $table) {
            $table->dropForeign('clients_id_foreign');
        });
    }

}
