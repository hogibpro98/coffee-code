<?php

namespace Database\Seeders;

use App\Models\Format;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class FormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Format::truncate();
        Schema::enableForeignKeyConstraints();
        $handle = fopen(database_path() . '/format.csv', 'r');
        $isHeader = true;
        while ($data = fgetcsv($handle)) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }
            $model = new Format();
            $model->fill([
                "is_private" => $data[0],
                "title" => $data[1],
                "content" => $data[2],
                "tag" => $data[3],
                "file_name" => $data[4],
                "file_path" => $data[5],
                "mime_type" => $data[6]
            ]);
            $model->save();
        }
    }
}
