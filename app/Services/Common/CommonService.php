<?php

namespace App\Services\Common;

use App\Traits\ListTrait;
use App\Models\User;
use App\Models\Interview;
use App\Models\MatterApplication;
use App\Models\Billing;
use App\Models\Inquiry;
use App\Exceptions\PosException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommonService
{
    use ListTrait;

    public function agent()
    {
        $this->query = User::all();

        return $this->query;
    }
    public function dashboard()
    {
        $data['interViewWait']          =   Interview::with('temporaryMember')->where('status', Interview::WAIT)->get();
        $data['interViewUndefined']     =   Interview::with('temporaryMember', 'temporaryMember.temporaryMemberQualifications', 'temporaryMember.temporaryMemberCareer')->where('status', Interview::UNDEFINED)->get();
        $data['interViewAlready']       =   Interview::with('temporaryMember', 'temporaryMember.temporaryMemberQualifications', 'temporaryMember.temporaryMemberCareer')->where('status', Interview::ALREADY)->get();

        $data['matterApplication']      =   MatterApplication::with('member','member.fieldTypes', 'matter')
                                            ->whereDate('matter_application_date', '>=', Carbon::now()->subdays(7)->format('Y-m-d') )
                                            ->whereDate('matter_application_date', '<=', Carbon::now()->format('Y-m-d') )
                                            ->get();

        $data['inquiries']              =    Inquiry::with(['member', 'user'])
                                            ->where('status', Inquiry::NO_ANSWER)
                                            ->where(function($query){
                                                $query->where('user_id', auth()->user()->id)->orWhere('user_id', null);
                                            })->get();

        $data['billingErrors']          =   Billing::with('member', 'billingDetails')->where('status', Billing::ERRORS)->get();

        return $data;
    }

}
