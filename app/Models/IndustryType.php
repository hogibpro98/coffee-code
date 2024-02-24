<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndustryType extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function matters()
    {
        return $this->hasMany(Matter::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
