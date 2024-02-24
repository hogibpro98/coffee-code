<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->longText('members_terms')->comment('会員規約');
            $table->longText('privacy_policy')->comment('個人情報取り扱い');
            $table->integer('professional_member_fee')->comment('Professional Member 会費');
            $table->integer('proAttend_partner_fee')->comment('ProAttend Partner 会費');
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
        Schema::dropIfExists('system_settings');
    }
}
