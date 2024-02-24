<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    const WAIT = 2;
    const UNDEFINED = 1;
    const ALREADY = 3;

    // idは書き換えたくないため保護
    protected $guarded = ['id'];

    // 仮会員テーブルにアクセスできるようにリレーションを記述
    public function temporaryMember()
    {
        return $this->belongsTo(TemporaryMember::class);
    }
}
