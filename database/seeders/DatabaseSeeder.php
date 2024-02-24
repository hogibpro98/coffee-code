<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(MailTemplateSeeder::class);
        $this->call(IndustryTypeSeeder::class);
        $this->call(FormatSeeder::class);
        $this->call(FormatTagSeeder::class);
        $this->call(LinkSeeder::class);
        $this->call(TemporaryMemberSeeder::class);
        $this->call(TemporaryMemberCareerSeeder::class);
        $this->call(TemporaryMemberQualificationSeeder::class);
        $this->call(TemporaryMemberEducationHistorySeeder::class);
        $this->call(TemporaryMemberCareerHistorySeeder::class);
        $this->call(TemporaryMemberFieldTypeSeeder::class);
        $this->call(MemberSeeder::class);
        // $this->call(MemberFirldTypeSeeder::class);
        $this->call(MemberEducationHistorySeeder::class);
        $this->call(MemberCareerHistorySeeder::class);
        $this->call(LeaveReasonSeeder::class);
        // $this->call(CreditcardSeeder::class);
        // $this->call(WorkingStatusSeeder::class);
        $this->call(FieldTypesTableSeeder::class);
        $this->call(BillingSeeder::class);
        // $this->call(BillingDetailSeeder::class);
        // $this->call(GmoTransactionSeeder::class);
        $this->call(InquirySeeder::class);
        $this->call(SystemSettingsSeeder::class);
        $this->call(BusinessCardSeeder::class);
    }
}
