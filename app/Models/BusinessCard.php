<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCard extends Model
{
    use HasFactory;

    const STATUS_NOT_SUPPORT = 1;
    const STATUS_SUPPORT = 2;
    const STATUS_CANCEL = 3;
    const STATUS_COMPLETE = 4;

    protected $guarded = ['id'];

    protected $casts = [
        'is_describe_office_name' => 'boolean',
        'card_qualification' => 'json'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class)->withTrashed();
    }
}
