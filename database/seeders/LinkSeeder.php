<?php

namespace Database\Seeders;

use App\Models\Link;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Link::truncate();
        Schema::enableForeignKeyConstraints();
        $handle = fopen(database_path() . '/link.csv', 'r');
        $isHeader = true;
        while ($data = fgetcsv($handle)) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }
            $model = new Link();
            $model->fill([
                "is_private" => $data[0],
                "title" => $data[1],
                "content" => $data[2],
                "image_name" => $data[3],
                "image_path" => $data[4],
                "url" => $data[5],
                "display_order" => $data[6],
            ]);
            $model->save();
        }
    }
}
