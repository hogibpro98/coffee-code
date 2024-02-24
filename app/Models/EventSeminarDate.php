<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventSeminarDate extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    public $timestamps = false;

    public function eventSeminar()
    {
        return $this->belongsTo(EventSeminar::class);
    }

    public function eventSeminarApplications()
    {
        return $this->hasMany(EventSeminarApplication::class);
    }
}
