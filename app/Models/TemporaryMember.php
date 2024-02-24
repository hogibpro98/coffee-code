<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryMember extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $attributes = [
        'interview_status' => 1,
    ];

    const INTERVIEW_STATUS_DISAPPROVAL = 4;
    const INTERVIEW_STATUS_APPROVAL = 5;

    protected $hidden = [
        'password',
    ];

    public function temporaryMemberCareer()
    {
        return $this->hasOne(TemporaryMemberCareer::class);
    }

    public function temporaryMemberQualifications()
    {
        return $this->hasMany(TemporaryMemberQualification::class);
    }

    public function temporaryMemberOwnedQualifications()
    {
        return $this->hasMany(TemporaryMemberOwnedQualification::class);
    }

    public function member()
    {
        return $this->hasOne(Member::class,'member_number','member_number');
    }

    public function interview()
    {
        return $this->hasOne(Interview::class);
    }
}
