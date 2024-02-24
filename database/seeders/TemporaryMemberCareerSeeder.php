<?php

namespace Database\Seeders;

use App\Models\TemporaryMemberCareer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TemporaryMemberCareerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TemporaryMemberCareer::factory()->count(110)->create();
    }
}
