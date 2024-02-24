<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $attributes = [
        'is_private' => true,
    ];

    public function formatTags()
    {
        return $this->hasMany(FormatTag::class);
    }
}
