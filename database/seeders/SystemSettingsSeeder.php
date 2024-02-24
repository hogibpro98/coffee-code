<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        SystemSetting::truncate();
        Schema::enableForeignKeyConstraints();
        DB::table('system_settings')->insert([
            'members_terms' => 'この利用規約（以下，「本規約」といいます。）は，＿＿＿＿＿（以下，「当社」といいます。）がこのウェブサイト上で提供するサービス（以下，「本サービス」といいます。）の利用条件を定めるものです。登録ユーザーの皆さま（以下，「ユーザー」といいます。）には，本規約に従って，本サービスをご利用いただきます。本規約は，ユーザーと当社との間の本サービスの利用に関わる一切の関係に適用されるものとします。            当社は本サービスに関し，本規約のほか，ご利用にあたってのルール等，各種の定め（以下，「個別規定」といいます。）をすることがあります。これら個別規定はその名称のいかんに関わらず，本規約の一部を構成するものとします。
            ',
            'privacy_policy' => 'ProAttendを運営する株式会社audience（以下「当社」といいます。）は、以下のとおり個人情報保護方針を定め、個人情報保護の仕組みを構築し、全従業員に個人情報保護の重要性の認識と取組みを徹底させることにより、個人情報の保護を推奨致します。当社は、お客様の個人情報の取得にあたっては、利用目的を明確にした上で、申告書等の書面、ホームページ等の画面、口頭等方法の如何を問わず、適法かつ公正な手段を用いて取得致します。',
            'professional_member_fee' => 10000,
            'proAttend_partner_fee' => 20000,
        ]);
    }
}
