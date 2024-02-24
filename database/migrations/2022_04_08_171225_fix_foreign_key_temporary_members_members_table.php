<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixForeignKeyTemporaryMembersMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temporary_members', function (Blueprint $table) {
            $table->dropForeign(['member_number']);
        });
        
        Schema::table('members', function (Blueprint $table) {
            $table->foreign('member_number')->references('member_number')->on('temporary_members');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['member_number']);
        });
        
        Schema::table('temporary_members', function (Blueprint $table) {
            $table->foreign('member_number')->references('member_number')->on('members');
        });
    }
}
