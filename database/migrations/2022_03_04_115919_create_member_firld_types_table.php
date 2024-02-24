<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberFirldTypesTable extends Migration
{
    // /**
    //  * Run the migrations.
    //  *
    //  * @return void
    //  */
    // public function up()
    // {
    //     Schema::create('member_firld_types', function (Blueprint $table) {
    //         $table->id();
    //         $table->unsignedBigInteger('member_id')->comment('本会員ID');
    //         $table->foreign('member_id')->references('id')->on('members')->onUpdate('cascade')->onDelete('cascade');
    //         $table->unsignedBigInteger('field_id')->comment('分野管理ID');
    //         $table->foreign('field_id')->references('id')->on('field_types')->onUpdate('cascade')->onDelete('cascade');
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down()
    // {
    //     Schema::dropIfExists('member_firld_types', function (Blueprint $table) {
    //         $table->dropForeign('members_id_foreign');
    //         $table->dropForeign('field_types_id_foreign');
    //     });
    // }
}
