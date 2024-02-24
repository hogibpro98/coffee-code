<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MailTemplate;
use Illuminate\Support\Facades\Schema;

class MailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        MailTemplate::truncate();
        Schema::enableForeignKeyConstraints();
        $handle = fopen(database_path() . '/mail_template.csv', 'r');
        $isHeader = true;
        while ($data = fgetcsv($handle)) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }
            $model = new MailTemplate();
            $model->fill([
                "template_id" => $data[0],
                "name" => $data[1],
                "type" => $data[2],
                "from" => $data[3],
                "title" => $data[4],
                "content" => $data[5]
            ]);
            $model->save();
        }
    }
}
