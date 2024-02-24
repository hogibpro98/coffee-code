<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InquiryCommentFile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function inquiryComment()
    {
        return $this->belongsTo(InquiryComment::class);
    }
}
