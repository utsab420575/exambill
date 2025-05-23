<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    use HasFactory;

    protected $fillable = ['type'];

    public function rateHeads()
    {
        return $this->hasMany(RateHead::class, 'exam_type');
    }
}
