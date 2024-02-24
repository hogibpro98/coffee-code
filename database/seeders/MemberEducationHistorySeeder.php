<?php

namespace Database\Seeders;

use App\Models\MemberEducationHistory;
use Illuminate\Database\Seeder;

class MemberEducationHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MemberEducationHistory::factory()->count(110)->create();
    }
}
