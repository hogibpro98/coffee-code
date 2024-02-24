<?php

namespace Database\Seeders;

use App\Models\FieldType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class FieldTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints(); // 外部キー制約を一旦無効化
        FieldType::truncate();
        Schema::enableForeignKeyConstraints();  // 外部キー制約を有効化

        // csv読み込みでデータをシーディングする
        $handle = fopen(database_path() . '/field_type.csv', 'r');
        $isHeader = true;
        while ($data = fgetcsv($handle)) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }
            $model = new FieldType();
            $model->fill([
                "grouping_list" => $data[0],
                "name" => $data[1],
            ]);
            $model->save();
        }
    }
}
