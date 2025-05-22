<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateAssign extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'rate_head_id', 'session_id','no_of_items','total_amount'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function rateHead()
    {
        return $this->belongsTo(RateHead::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
