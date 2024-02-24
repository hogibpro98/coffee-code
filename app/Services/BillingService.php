<?php


namespace App\Services;


use App\Exceptions\PosException;
use App\Mail\MainMailable;
use App\Models\Billing;
use App\Models\BillingDetail;
use App\Models\Member;
use App\Traits\ListTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingService
{
    use ListTrait;

    public function index($params)
    {
        DB::connection()->enableQueryLog();

        $this->query = Billing::with('billingDetails', 'Member');
        $this->params = $params;

        if(isset($params['name'])) {
            $this->query->whereHas('Member', function($r) use ($params){
                $r->where('name_kanji', 'LIKE', '%'.$params['name']. '%');
                $r->orwhere('name_furigana', 'LIKE', '%'.$params['name']. '%');
            }); 
        }

        if (!empty($params['member_number'])) {
            $this->query->whereHas('Member', function($r) use ($params){
                $r->where('member_number', 'LIKE', '%'.$params['member_number']. '%');
            });
        }

        if( !empty($params['status']) && is_array($params['status'])){
            $this->query->whereIn('status', $params['status']);
        }
        
        if( !empty($params['kinds']) && !empty($params['kinds'][0])){
            $this->query->where('billing_number', 'LIKE', '%'.'-M-'. '%');
        }

        if( !empty($params['kinds']) && !empty($params['kinds'][1]) ){
            
            $this->query->where('billing_number', 'LIKE', '%'.'-S-'. '%');
        }
        
        if( !empty($params['start_settlement_date']) && !empty($params['end_settlement_date']))
        {
            $this->query->whereBetween('settlement_date', array($params['start_settlement_date'],$params['end_settlement_date'] ));
        }
        $this->addParams('billing_number', true);
        $this->addParams('billing_month', true);
        $this->addParams('is_not_billing', true);

        return $this->query();
    }

    public function show($id)
    {
        $data = Billing::with('billingDetails', 'Member')->find($id);

        if(!$data) {
            throw new PosException('13', '001', 404);
        }
        return $data;
    }

    public function pdf($id)
    {
        $data = Billing::with('billingDetails')->find($id);
        
        if(!$data) {
            throw new PosException('13', '002', 404);
        }

        $pdf = PDF::loadView('billing_pdf', compact('data'))->setPaper('a4','landscape');
        return $pdf->download('billingPDF.pdf');
    }


    // TODO: ※後日修正
    // public function retry()
    // {
    // }

    public function update($params, $id)
    {
        return DB::transaction(function () use ($params, $id) {

            $data = Billing::with('billingDetails')->find($id);

            if(!$data) {
                throw new PosException('13', '003', 404);
            }

            $billingDetails = new BillingDetail;
            $oldBillDetails = $billingDetails->where('billing_id', $id)->get();
            if(isset($oldBillDetails) && count($oldBillDetails) > 0)
            {
                foreach($oldBillDetails as $key => $item)
                {
                    $billDetailId[] = $item->id;
                }
                $billingDetails->destroy($billDetailId);
            }

            if(!empty($params['billing_details']))
            {
                $totalPrice = 0;

                foreach($params['billing_details'] as $k => $v)
                {

                    $totalPrice += $v['price'];

                    $model = new BillingDetail;
                    $model->billing_id = $id;
                    $model->name = $v['name'];
                    $model->price = $v['price'];
                    $model->save();
                }

                $data->subtotal = $totalPrice;
                $data->tax = ($totalPrice * config('app.tax')) / 100;
                $data->total = $totalPrice + ( ($totalPrice * config('app.tax') ) / 100);
                $data->member_note  = $params['member_note'] ?? $data->member_note;
                $data->user_note = $params['user_note'] ?? $data->user_note;
                $data->save();
            }

            return $data;
        });


    }

    function fix($params)
    {
        $year = explode('-', $params)[0] ?? 0;
        $month = explode('-', $params)[1] ?? 0;
        
        $data = Billing::whereYear('billing_month', '=', $year)
                        ->whereMonth('billing_month', '=', $month)
                        ->get();

        if(!$data->toArray()) {
            throw new PosException('13', '004', 404);
        }
        
        if(in_array(Billing::STATUS_UNCLAIMED, $data->pluck('status')->toArray()) == false)
        {
            throw new PosException('13', '005', 400);
        }

        foreach($data as $k => $v)
        {
            if($v->status == Billing::STATUS_UNCLAIMED)
            {
                $model = Billing::find($v->id);
                $model->status = Billing::STATUS_FIX;
                $model->update();
                
                $member = Member::find($v->member_id);
                if(strpos('-M-',$v->billing_number))
                {
                    $mailTemplate = new MainMailable(16);
                    $mailTemplate->setViewData([
                        'name' => $member->name_kanji,
                        'title' => BillingDetail::where('billing_id',$v->id)->value('name'),
                        'total' => $v->total,
                        'url' => config('app.member_site_url').'/mypage',
                    ]);
                    $mailTemplate->sendMail($member->email);        
                }

                if(strpos('-S-',$v->billing_number))
                {
                    $mailTemplate = new MainMailable(17);
                    $mailTemplate->setViewData([
                        'name' => $member->name_kanji,
                        'title' => BillingDetail::where('billing_id',$v->id)->value('name'),
                        'total' => $v->total,
                        'url' => config('app.member_site_url').'/mypage',
                    ]);
                    $mailTemplate->sendMail($member->email);        
                }

                if(!strpos('-M-',$v->billing_number) && !strpos('-S-',$v->billing_number))
                {
                    $mailTemplate = new MainMailable(17);
                    $mailTemplate->setViewData([
                        'name' => $member->name_kanji,
                        'title' => BillingDetail::where('billing_id',$v->id)->value('name'),
                        'total' => $v->total,
                        'url' => config('app.member_site_url').'/mypage',
                    ]);
                    $mailTemplate->sendMail($member->email);        
                }
            }
        }
        return $model;
    }

    function exclude($params)
    {
        $data = Billing::whereIn('id', $params['billing_id'])->get();

        if(count($data) == 0) {
            throw new PosException('13', '006', 404);
        }

        foreach($data as $k => $v)
        {
            if($v->status != Billing::STATUS_PAID)
            {
                $model = Billing::find($v->id);
                $model->is_not_billing = true;
                $model->update();
            }
        }

        return $data;
    }

    function target($params)
    {
        $data = Billing::whereIn('id', $params['billing_id'])->get();

        if(count($data) == 0) {
            throw new PosException('13', '007', 404);
        }

        foreach($data as $k => $v)
        {
            if($v->status != Billing::STATUS_PAID)
            {
                $model = Billing::find($v->id);
                $model->is_not_billing = false;
                $model->update();
            }
        }

        return $data;
    }

    public function updateBill($id)
    {   
        $data = Billing::find($id);

        if(!$data) {
            throw new PosException('13', '008', 404);
        }
        
        if($data->status != Billing::STATUS_UNCLAIMED)
        {
            throw new PosException('13', '009', 400);
        }
        if($data->status == Billing::STATUS_UNCLAIMED)
        {
            $data->status = Billing::STATUS_FIX;
            $data->update();

            $member = Member::find($data->member_id);
            if(strpos('-M-',$data->billing_number))
            {
                $mailTemplate = new MainMailable(16);
                $mailTemplate->setViewData([
                    'name' => $member->name_kanji,
                    'title' => BillingDetail::where('billing_id',$data->id)->value('name'),
                    'total' => $data->total,
                    'url' => config('app.member_site_url').'/mypage',
                ]);
                $mailTemplate->sendMail($member->email);        
            }

            if(strpos('-S-',$data->billing_number))
            {
                $mailTemplate = new MainMailable(17);
                $mailTemplate->setViewData([
                    'name' => $member->name_kanji,
                    'title' => BillingDetail::where('billing_id',$data->id)->value('name'),
                    'total' => $data->total,
                    'url' => config('app.member_site_url').'/mypage',
                ]);
                $mailTemplate->sendMail($member->email);        
            }

            if(!strpos('-M-',$data->billing_number) && !strpos('-S-',$data->billing_number))
            {
                $mailTemplate = new MainMailable(17);
                $mailTemplate->setViewData([
                    'name' => $member->name_kanji,
                    'title' => BillingDetail::where('billing_id',$data->id)->value('name'),
                    'total' => $data->total,
                    'url' => config('app.member_site_url').'/mypage',
                ]);
                $mailTemplate->sendMail($member->email);        
            }
        }

        return $data;
    }

    public function cancel($id)
    {
        $data = Billing::find($id);

        if(!$data) {
            throw new PosException('13', '010', 404);
        }
        
        if($data->status != Billing::STATUS_FIX)
        {
            throw new PosException('13', '011', 400);
        }

        if($data->status == Billing::STATUS_FIX)
        {
            $data->status = Billing::STATUS_UNCLAIMED;
            $data->update();
        }
        
        return $data;
    }
}
