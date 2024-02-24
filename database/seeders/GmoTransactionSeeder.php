<?php

namespace Database\Seeders;

use App\Models\GmoTransaction;
use Illuminate\Database\Seeder;

class GmoTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GmoTransaction::factory()->count(200)->create();
    }
}
