<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldType extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'field_member', 'field_id', 'member_id');
    }

    public function fieldTypeMatters()
    {
        return $this->hasMany(FieldTypeMatter::class);
    }
}
