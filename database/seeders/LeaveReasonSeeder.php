<?php

namespace Database\Seeders;

use App\Models\LeaveReason;
use Illuminate\Database\Seeder;

class LeaveReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LeaveReason::factory()->count(10)->create();
    }
}
