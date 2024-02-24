<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    const NO_PAYMENT_REQUEST = 1;
    const PAYMENT_COMFIRM = 2;
    const SETTLEMENT = 3;
    const ERRORS = 4;

    protected $guarded = ['id'];

    protected $attributes = [
        'is_not_billing' => false,
        'applied_at' => null
    ];

    // 未請求
    const STATUS_UNCLAIMED = 1;
    // 請求確定
    const STATUS_FIX = 2;
    // 決済完了
    const STATUS_PAID = 3;
    // エラー
    const STATUS_ERROR = 4;

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function billingDetails()
    {
        return $this->hasMany(BillingDetail::class);
    }

    public function gmoTransactions()
    {
        return $this->hasMany(GmoTransaction::class);
    }
}
