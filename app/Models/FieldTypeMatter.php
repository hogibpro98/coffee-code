<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldTypeMatter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function matter()
    {
        return $this->belongsTo(Matter::class);
    }

    public function fieldType()
    {
        return $this->belongsTo(FieldType::class);
    }
}
