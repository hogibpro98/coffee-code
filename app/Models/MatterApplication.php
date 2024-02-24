<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatterApplication extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function matter()
    {
        return $this->belongsTo(Matter::class);
    }
}
