<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryMemberCareer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function temporaryMember()
    {
        return $this->belongsTo(TemporaryMember::class);
    }

    public function temporaryMemberEducationHistories()
    {
        return $this->hasMany(TemporaryMemberEducationHistory::class);
    }

    public function temporaryMemberCareerHistories()
    {
        return $this->hasMany(TemporaryMemberCareerHistory::class);
    }

    public function temporaryMemberFieldTypes()
    {
        return $this->hasMany(TemporaryMemberFieldType::class);
    }

    public function temporaryMemberOwnedQualifications()
    {
        return $this->hasMany(TemporaryMemberOwnedQualification::class);
    }
}
