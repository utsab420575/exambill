<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['ugr_id', 'session', 'year', 'semester'];

    public function rateAmounts()
    {
        return $this->hasMany(RateAmount::class);
    }

    public function rateAssigns()
    {
        return $this->hasMany(RateAssign::class);
    }
}
