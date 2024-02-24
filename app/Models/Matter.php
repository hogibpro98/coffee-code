<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matter extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_BEFORE_THE_APPLICATION_PERIOD = 1;
    const STATUS_DURING_THE_APPLICATION_PERIOD= 2;
    const STATUS_END_OF_THE_APPLICATION_PERIOD= 3;
    const STATUS_END_OF_RECRUITMENT= 4;
    const STATUS_PRIVATE = 5;

    protected $guarded = ['id'];

    public function industryType()
    {
        return $this->belongsTo(IndustryType::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function matterUserAssigns()
    {
        return $this->hasMany(MatterUserAssign::class);
    }

    public function matterMemberAssigns()
    {
        return $this->hasMany(MatterMemberAssign::class);
    }

    public function matterApplications()
    {
        return $this->hasMany(MatterApplication::class);
    }

    public function fieldTypeMatters()
    {
        return $this->hasMany(FieldTypeMatter::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class,"matter_member_assigns", "matter_id", "member_id");
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,"matter_user_assigns", "matter_id", "user_id");
    }
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Member::class,"matter_applications", "matter_id", "member_id")->withPivot('status','automatic_email_send_time');
    }
    public function fieldTypes(): BelongsToMany
    {
        return $this->belongsToMany(FieldType::class,"field_type_matters", "matter_id", "field_type_id");
    }
}
