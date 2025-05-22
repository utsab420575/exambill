<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateAmount extends Model
{
    use HasFactory;

    protected $fillable = ['default_rate', 'min_rate', 'max_rate', 'session_id', 'rate_head_id'];

    public function rateHead()
    {
        return $this->belongsTo(RateHead::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
