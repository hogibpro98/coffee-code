<?php

namespace Database\Seeders;

use App\Models\TemporaryMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TemporaryMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        TemporaryMember::truncate();
        Schema::enableForeignKeyConstraints();
        $handle = fopen(database_path() . '/temporary_member.csv', 'r');
        $isHeader = true;
        while ($data = fgetcsv($handle)) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }
            $model = new TemporaryMember();
            $model->fill([
                "member_number" => $data[0],
                "name_kanji" => $data[1],
                "name_furigana" => $data[2],
                "email" => $data[3],
                "password" => $data[4],
                "interview_status" => $data[5]
            ]);
            $model->save();
        }
    }
}
