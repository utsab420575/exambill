<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateHead extends Model
{
    use HasFactory;

    protected $fillable = [
        'head', 'sub_head', 'exam_type', 'order_no', 'dist_type',
        'enable_min', 'enable_max', 'is_course', 'is_student_count',
        'marge_with', 'status'
    ];

    public function examType()
    {
        return $this->belongsTo(ExamType::class, 'exam_type');
    }

    public function mergedWith()
    {
        return $this->belongsTo(RateHead::class, 'marge_with');
    }

    public function rateAmounts()
    {
        return $this->hasMany(RateAmount::class);
    }

    public function rateAssigns()
    {
        return $this->hasMany(RateAssign::class);
    }
}
