<?php

namespace Database\Seeders;

use App\Models\TemporaryMemberCareerHistory;
use Illuminate\Database\Seeder;

class TemporaryMemberCareerHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TemporaryMemberCareerHistory::factory()->count(110)->create();
    }
}
