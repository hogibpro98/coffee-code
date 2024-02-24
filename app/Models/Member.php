<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $attributes = [
        'is_partner' => false,
        'is_release_working_status' => false,
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verified_at',
    ];

    public function temporaryMember()
    {
        return $this->belongsTo(TemporaryMember::class, 'member_number', 'member_number');
    }

    public function fieldTypes(): BelongsToMany
    {
        return $this->belongsToMany(FieldType::class, 'field_member', 'member_id', 'field_id')->withPivot('type');
    }

    public function memberEducationHistories()
    {
        return $this->hasMany(MemberEducationHistory::class);
    }

    public function memberCareerHistories()
    {
        return $this->hasMany(MemberCareerHistory::class);
    }

    public function memberOwnedQualifications()
    {
        return $this->hasMany(MemberOwnedQualification::class);
    }

    public function leaveReasons()
    {
        return $this->hasMany(LeaveReason::class);
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function creditcards()
    {
        return $this->hasMany(Creditcard::class);
    }

    public function workingStatuses()
    {
        return $this->hasMany(WorkingStatus::class);
    }

    public function inquiry()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function businessCards()
    {
        return $this->hasMany(BusinessCard::class);
    }

    public function matterMemberAssigns()
    {
        return $this->hasMany(MatterMemberAssign::class);
    }

    public function matterApplications()
    {
        return $this->hasMany(MatterApplication::class);
    }

    public function eventSeminarApplications()
    {
        return $this->hasMany(EventSeminarApplication::class);
    }

    public function inquiryComments()
    {
        return $this->hasMany(InquiryComment::class);
    }
}
