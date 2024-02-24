<?php

namespace Database\Seeders;

use App\Models\FormatTag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class FormatTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        FormatTag::truncate();
        Schema::enableForeignKeyConstraints();
        $handle = fopen(database_path() . '/format_tag.csv', 'r');
        $isHeader = true;
        while ($data = fgetcsv($handle)) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }
            $model = new FormatTag();
            $model->fill([
                "format_id" => $data[0],
                "name" => $data[1],
            ]);
            $model->save();
        }
    }
}
