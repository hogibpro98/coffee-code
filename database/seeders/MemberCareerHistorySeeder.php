<?php

namespace Database\Seeders;

use App\Models\MemberCareerHistory;
use Illuminate\Database\Seeder;

class MemberCareerHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MemberCareerHistory::factory()->count(110)->create();
    }
}
