<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmoTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }
}
