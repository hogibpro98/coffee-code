<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRepresentative extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function clients()
    {
        return $this->belongsTo(Client::class);
    }
}
