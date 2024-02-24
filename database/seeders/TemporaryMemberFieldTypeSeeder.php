<?php

namespace Database\Seeders;

use App\Models\TemporaryMemberFieldType;
use Illuminate\Database\Seeder;

class TemporaryMemberFieldTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TemporaryMemberFieldType::factory()->count(110)->create();
    }
}
