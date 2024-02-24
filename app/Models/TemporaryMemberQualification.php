<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryMemberQualification extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    public function temporaryMember()
    {
        return $this->belongsTo(TemporaryMember::class);
    }
}
