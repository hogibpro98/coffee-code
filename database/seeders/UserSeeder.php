<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::disableForeignKeyConstraints();
        \App\Models\User::truncate();
        Schema::enableForeignKeyConstraints();
        $handle = fopen(database_path() . '/users.csv', 'r');
        $isHeader = true;
        while ($data = fgetcsv($handle)) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }
            $model = new \App\Models\User();
            $model->fill([
                'name' => $data[1],
                'email' => $data[2],
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'belong' => $data[3],
                'expiration_date' => $data[4] ? $data[4] : null,
                'deleted_at' => $data[5] ? $data[5] : null,
                'remember_token' => \Str::random(10),
            ]);
            $model->save();
        }
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
