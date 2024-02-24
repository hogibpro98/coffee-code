<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InquiryComment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function inquiryCommentFiles()
    {
        return $this->hasMany(InquiryCommentFile::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
