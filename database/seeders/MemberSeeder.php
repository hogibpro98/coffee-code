<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Member::truncate();
        Schema::enableForeignKeyConstraints();
        $handle = fopen(database_path() . '/member.csv', 'r');
        $isHeader = true;
        while ($data = fgetcsv($handle)) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }
            $model = new Member();
            $model->fill([
                "member_number" => $data[0],
                "name_kanji" => $data[1],
                "name_furigana" => $data[2],
                "email" => $data[3],
                "email_verified_at" => $data[4],
                "remember_token" => $data[5],
                "password" => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                "birthdate" => $data[7],
                "gender" => $data[8],
                "office_name" => $data[9],
                "postal_code" => $data[10],
                "prefecture" => $data[11],
                "address1" => $data[12],
                "address2" => $data[13],
                "tel1" => $data[14],
                "tel2" => $data[15],
                "tel3" => $data[16],
                "certified_accountant_number" => $data[18] ? $data[18] : null,
                "tax_accountant_number" => $data[19] ? $data[19] : null,
                "advisory_experience_years" => $data[20],
                "other_specialized_field" => $data[21] ? $data[21] : null,
                "experience" => $data[22],
                "is_partner" => $data[23] ? $data[23] : false,
                "is_release_working_status" => $data[24] ? $data[24] : false,
            ]);
            $model->save();
        }
    }
}
