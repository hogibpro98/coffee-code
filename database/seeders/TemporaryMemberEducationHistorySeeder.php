<?php

namespace Database\Seeders;

use App\Models\TemporaryMemberEducationHistory;
use Illuminate\Database\Seeder;

class TemporaryMemberEducationHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TemporaryMemberEducationHistory::factory()->count(110)->create();
    }
}
