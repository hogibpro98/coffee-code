<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryMemberEducationHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    public function temporaryMemberCareer()
    {
        return $this->belongsTo(TemporaryMemberCareer::class);
    }
}
