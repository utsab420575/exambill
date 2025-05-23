<?php

namespace App\Http\Controllers;

use App\Models\RateAmount;
use App\Models\RateHead;
use App\Models\Session;
use App\Models\Teacher;
use App\Services\ApiData;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
class PdfController extends Controller
{
    //
    public function allReportRegularSessions(){
        $sessions=ApiData::getPreviousRegularSessions();
        return view('ReportSessions.all_regular_session')->with('sessions',$sessions);
    }
    public function allReportReviewSessions(){
        $sessions=ApiData::getPreviousRegularSessions();
        return view('ReportSessions.all_review_session')->with('sessions',$sessions);
    }

    public function reportSessionsRegularGenerate($sid,Request $request){
        $session_info = Session::where('ugr_id', $sid)->first();
        $teachers = Teacher::with([
            'user',
            'designation',
            'rateAssigns',
        ])->whereHas('rateAssigns', function ($query) use ($session_info) {
            $query->where('session_id', $session_info->id);
        })->get();


        $rateHead_order_1 = RateHead::where('order_no', 1)->first();
        // Assuming $session_info is already available
        $rateAmount_order_1 = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', 1);
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple


        $rateHead_order_2 = RateHead::where('order_no', 2)->first();
        // Assuming $session_info is already available
        $rateAmount_order_2 = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', 2);
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple


        $rateHead_order_3 = RateHead::where('order_no', 3)->first();
        // Assuming $session_info is already available
        $rateAmount_order_3 = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', 3);
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple





        //Order 4
        $rateHead_order_4 = RateHead::where('order_no', 4)->first();
        // Assuming $session_info is already available
        $rateAmount_order_4 = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', 4);
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple


        //Order 5
        $rateHead_order_5 = RateHead::where('order_no', 5)->first();
        // Assuming $session_info is already available
        $rateAmount_order_5 = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', 5);
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple


        //Order 6.a
        $rateHead_order_6a = RateHead::where('order_no', '6.a')->first();
        // Assuming $session_info is already available
        $rateAmount_order_6a = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '6.a');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple

        //Order 6.b
        $rateHead_order_6b = RateHead::where('order_no', '6.b')->first();
        // Assuming $session_info is already available
        $rateAmount_order_6b = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '6.b');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple

        //Order 6.c
        $rateHead_order_6c = RateHead::where('order_no', '6.c')->first();
        // Assuming $session_info is already available
        $rateAmount_order_6c = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '6.c');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple

        //Order 6.d
        $rateHead_order_6d = RateHead::where('order_no', '6.d')->first();
        // Assuming $session_info is already available
        $rateAmount_order_6d = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '6.d');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple





        //Order 7.e
        $rateHead_order_7e = RateHead::where('order_no', '7.e')->first();
        // Assuming $session_info is already available
        $rateAmount_order_7e = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '7.e');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple

        //Order 7.f
        $rateHead_order_7f = RateHead::where('order_no', '7.f')->first();
        // Assuming $session_info is already available
        $rateAmount_order_7f = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '7.f');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple



        //Order 8.a
        $rateHead_order_8a = RateHead::where('order_no', '8.a')->first();
        // Assuming $session_info is already available
        $rateAmount_order_8a = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '8.a');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple

        //Order 8.b
        $rateHead_order_8b = RateHead::where('order_no', '8.b')->first();
        // Assuming $session_info is already available
        $rateAmount_order_8b = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '8.b');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple

        //Order 8.c
        $rateHead_order_8c = RateHead::where('order_no', '8.c')->first();
        // Assuming $session_info is already available
        $rateAmount_order_8c = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '8.c');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple

        //Order 8.d
        $rateHead_order_8d = RateHead::where('order_no', '8.d')->first();
        // Assuming $session_info is already available
        $rateAmount_order_8d = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '8.d');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple



        //Order 9
        $rateHead_order_9 = RateHead::where('order_no', '9')->first();
        // Assuming $session_info is already available
        $rateAmount_order_9 = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '9');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple





        //Order 10.a
        $rateHead_order_10_a = RateHead::where('order_no', '10.a')->first();
        // Assuming $session_info is already available
        $rateAmount_order_10_a = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '10.a');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple


        //Order 10.b
        $rateHead_order_10_b = RateHead::where('order_no', '10.b')->first();
        // Assuming $session_info is already available
        $rateAmount_order_10_b = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '10.b');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple


        //Order 11
        $rateHead_order_11 = RateHead::where('order_no', '11')->first();
        // Assuming $session_info is already available
        $rateAmount_order_11 = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '11');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple



        //Order 12.a
        $rateHead_order_12_a = RateHead::where('order_no', '12.a')->first();
        // Assuming $session_info is already available
        $rateAmount_order_12_a = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '12.a');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple


        //Order 12.b
        $rateHead_order_12_b = RateHead::where('order_no', '12.b')->first();
        // Assuming $session_info is already available
        $rateAmount_order_12_b = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '12.b');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple


        //Order 13
        $rateHead_order_13 = RateHead::where('order_no', '13')->first();
        // Assuming $session_info is already available
        $rateAmount_order_13 = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '13');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple



        //Order 14
        $rateHead_order_14 = RateHead::where('order_no', '14')->first();
        //dd($rateHead_order_14);
        // Assuming $session_info is already available
        $rateAmount_order_14 = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '14');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple

        //dd($rateAmount_order_14);


        //Order 15
        $rateHead_order_15 = RateHead::where('order_no', '15')->first();
        // Assuming $session_info is already available
        $rateAmount_order_15 = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '15');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple


        //Order 16
        $rateHead_order_16 = RateHead::where('order_no', '16')->first();
        // Assuming $session_info is already available
        $rateAmount_order_16 = RateAmount::where('session_id', $session_info->id)
            ->whereHas('rateHead', function ($query) {
                $query->where('order_no', '16');
            })
            ->with('rateHead')
            ->first(); // or get() if you expect multiple
        //dd($rateAmount_order_1);

        $pdf = Pdf::loadView('ReportSessions.regular_session_report_download', [
            'teachers' => $teachers,
            'session_info' => $session_info,

            'rateHead_order_1' => $rateHead_order_1,
            'rateAmount_order_1'=>$rateAmount_order_1,
            'rateHead_order_2' => $rateHead_order_2,
            'rateAmount_order_2'=>$rateAmount_order_2,
            'rateHead_order_3' => $rateHead_order_3,
            'rateAmount_order_3'=>$rateAmount_order_3,
            'rateHead_order_4' => $rateHead_order_4,
            'rateAmount_order_4'=>$rateAmount_order_4,
            'rateHead_order_5' => $rateHead_order_5,
            'rateAmount_order_5'=>$rateAmount_order_5,

            'rateHead_order_6a' => $rateHead_order_6a,
            'rateAmount_order_6a'=>$rateAmount_order_6a,
            'rateHead_order_6b' => $rateHead_order_6b,
            'rateAmount_order_6b'=>$rateAmount_order_6b,
            'rateHead_order_6c' => $rateHead_order_6c,
            'rateAmount_order_6c'=>$rateAmount_order_6c,
            'rateHead_order_6d' => $rateHead_order_6d,
            'rateAmount_order_6d'=>$rateAmount_order_6d,

            'rateHead_order_7e' => $rateHead_order_7e,
            'rateAmount_order_7e'=>$rateAmount_order_7e,
            'rateHead_order_7f' => $rateHead_order_7f,
            'rateAmount_order_7f'=>$rateAmount_order_7f,

            'rateHead_order_8a' => $rateHead_order_8a,
            'rateAmount_order_8a'=>$rateAmount_order_8a,
            'rateHead_order_8b' => $rateHead_order_8b,
            'rateAmount_order_8b'=>$rateAmount_order_8b,
            'rateHead_order_8c' => $rateHead_order_8c,
            'rateAmount_order_8c'=>$rateAmount_order_8c,
            'rateHead_order_8d' => $rateHead_order_8d,
            'rateAmount_order_8d'=>$rateAmount_order_8d,

            'rateHead_order_9' => $rateHead_order_9,
            'rateAmount_order_9'=>$rateAmount_order_9,


            'rateHead_order_10_a' => $rateHead_order_10_a,
            'rateAmount_order_10_a'=>$rateAmount_order_10_a,
            'rateHead_order_10_b' => $rateHead_order_10_b,
            'rateAmount_order_10_b'=>$rateAmount_order_10_b,

            'rateHead_order_11' => $rateHead_order_11,
            'rateAmount_order_11'=>$rateAmount_order_11,


            'rateHead_order_12_a' => $rateHead_order_12_a,
            'rateAmount_order_12_a'=>$rateAmount_order_12_a,
            'rateHead_order_12_b' => $rateHead_order_12_b,
            'rateAmount_order_12_b'=>$rateAmount_order_12_b,

            'rateHead_order_13' => $rateHead_order_13,
            'rateAmount_order_13'=>$rateAmount_order_13,

            'rateHead_order_14' => $rateHead_order_14,
            'rateAmount_order_14'=>$rateAmount_order_14,

            'rateHead_order_15' => $rateHead_order_15,
            'rateAmount_order_15'=>$rateAmount_order_15,

            'rateHead_order_16' => $rateHead_order_16,
            'rateAmount_order_16'=>$rateAmount_order_16,

        ])->setPaper('legal', 'portrait'); // or 'landscape';


        return $pdf->stream('demo_exam_bill.pdf');
       // return $pdf->download('demo_exam_bill.pdf');
    }

}
