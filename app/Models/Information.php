<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Information extends Model
{
    use HasFactory,SoftDeletes;

    const BEFORE_PUBLIC = 1;
    const BEING_PUBLISHED = 2;
    const END_OF_PUBLIC = 3;
    const NOT_PUBLISHED = 4;
    // idは書き換えたくないため保護
    protected $guarded = ['id'];
    protected $dates = [
        'display_start_date',
        'display_end_date',
    ];
}
