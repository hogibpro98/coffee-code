<?php

namespace Database\Seeders;

use App\Models\TemporaryMemberQualification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TemporaryMemberQualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        TemporaryMemberQualification::truncate();
        Schema::enableForeignKeyConstraints();
        $handle = fopen(database_path() . '/temporary_member_qualification.csv', 'r');
        $isHeader = true;
        while ($data = fgetcsv($handle)) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }
            $model = new TemporaryMemberQualification();
            $model->fill([
                "temporary_member_id" => $data[0],
                "qualification" => $data[1]
            ]);
            $model->save();
        }
    }
}
