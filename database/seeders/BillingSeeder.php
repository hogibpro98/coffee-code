<?php

namespace Database\Seeders;

use App\Models\Billing;
use App\Models\BillingDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Billing::truncate();
        BillingDetail::truncate();
        Schema::enableForeignKeyConstraints();
        $handle = fopen(database_path() . '/billing.csv', 'r');
        $isHeader = true;
        while ($data = fgetcsv($handle)) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }
            $model = new Billing();
            logger((string)$data[0]);
            $model->fill([
                "status" => $data[0],
                "billing_number" => $data[1],
                "member_id" => $data[2],
                "subtotal" => $data[3],
                "tax" => (int)$data[3] * config('app.tax') / 100,
                "total" => (int)$data[3] + (int)$data[3] * config('app.tax') / 100,
                "billing_month" => $data[4],
                "settlement_date" => $data[5],
                "message" => $data[0] === 4 ? 'エラーメッセージ' : null ,
                "member_note" => $data[6],
                "user_note" => $data[7],
                "is_not_billing" => $data[0] === 1 || $data[0] === 2 ? rand(1,3) === 1 : false,
                "applied_at" => $data[8],
            ]);
            $model->save();
            
            $detail_model = new BillingDetail();
            $detail_model->fill([
                "billing_id" => $data[9],
                "name" => $data[10],
                // "note" => $data[11],
                "price" => $data[3],
            ]);
            $detail_model->save();
        }
    }
}
