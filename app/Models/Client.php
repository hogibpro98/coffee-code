<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function industryType()
    {
        return $this->belongsTo(IndustryType::class);
    }

    public function clientRepresentatives()
    {
        return $this->hasMany(ClientRepresentative::class, 'client_id', 'id');
    }

    public function matters()
    {
        return $this->hasMany(Matter::class, 'client_id', 'id');
    }
}
