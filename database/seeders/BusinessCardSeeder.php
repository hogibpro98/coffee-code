<?php

namespace Database\Seeders;

use App\Models\BusinessCard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class BusinessCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        BusinessCard::truncate();
        Schema::enableForeignKeyConstraints();
        $handle = fopen(database_path() . '/business_card.csv', 'r');
        $isHeader = true;
        while ($data = fgetcsv($handle)) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }
            $card_image = config('filesystems.disks.s3.bucket'). '/business-card/'. $data[0].'/card_image.jpg';
            $card_background_image = config('filesystems.disks.s3.bucket'). '/business-card/'. $data[0].'/card_background_image.jpg';
            $model = new BusinessCard();
            $model->fill([
                "member_id" => $data[0],
                "status" => $data[12],
                "delivery_postal_code" => $data[5],
                "delivery_prefecture" => $data[6],
                "delivery_address1" => $data[7],
                "delivery_address2" => $data[8],
                "card_name_kanji" => $data[1],
                "card_name_roman" => $data[2],
                "card_email" => $data[3],
                "card_office_name" => $data[4],
                "is_describe_office_name" => rand(0,1) == 1,
                "card_qualification" => ['資格A','資格B'],
                "note" => 'note abc',
                "card_image" => $card_image,
                "card_background_image" => $card_background_image
            ]);
            $model->save();
            Storage::put($card_image, file_get_contents(database_path() . '/sample_card_image.jpg'), 'public');
            Storage::put($card_background_image, file_get_contents(database_path() . '/sample_card_background_image.jpg'), 'public');
        }
    }
}
