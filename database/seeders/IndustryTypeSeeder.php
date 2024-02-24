<?php

namespace Database\Seeders;

use App\Models\IndustryType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class IndustryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        IndustryType::truncate();
        Schema::enableForeignKeyConstraints();
        $handle = fopen(database_path() . '/industry_type.csv', 'r');
        $isHeader = true;
        while ($data = fgetcsv($handle)) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }
            $model = new IndustryType();
            $model->fill([
                "name" => $data[0],
                "note" => $data[1]
            ]);
            $model->save();
        }
    }
}
