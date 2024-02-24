<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    const NO_ANSWER = 1;
    const ANSWERED = 2;
    const SUPPORT_EMAL = 3;
    const COMPLETE = 4;

    protected $guarded = ['id'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inquiryComments()
    {
        return $this->hasMany(InquiryComment::class);
    }
}
