<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormatTag extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function format()
    {
        return $this->belongsTo(Format::class);
    }
}
