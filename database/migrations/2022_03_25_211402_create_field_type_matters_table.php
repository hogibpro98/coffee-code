<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldTypeMattersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_type_matters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matter_id')->comment('案件ID');
            $table->foreign('matter_id')->references('id')->on('matters')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('field_type_id')->comment('分野ID');
            $table->foreign('field_type_id')->references('id')->on('field_types')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('field_type_matters', function (Blueprint $table) {
            $table->dropForeign('matters_id_foreign');
            $table->dropForeign('field_types_id_foreign');
        });
    }
}
