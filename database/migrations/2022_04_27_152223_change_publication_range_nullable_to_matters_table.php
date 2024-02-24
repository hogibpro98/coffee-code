<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePublicationRangeNullableToMattersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matters', function (Blueprint $table) {
            DB::statement('alter table matters modify column publication_range tinyint unsigned comment "公開範囲"');
            DB::statement('alter table matters modify column contract_status tinyint unsigned comment "	契約ステータス"');
            DB::statement('alter table matters modify column matter_status tinyint unsigned comment "	案件ステータス"');
            $table->unsignedBigInteger('client_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matters', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable(false)->change();
            DB::statement('alter table matters modify column matter_status tinyint unsigned not null comment "案件ステータス"');
            DB::statement('alter table matters modify column contract_status tinyint unsigned not null comment "契約ステータス"');
            DB::statement('alter table matters modify column publication_range tinyint unsigned not null comment "公開範囲"');
        });
    }
}
