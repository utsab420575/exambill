<?php
namespace App\Services;


use Illuminate\Http\Request;

class ApiData{
    //get all previous session which is not active now
    //1/1,2/1,3/1,4/1,5/1 5 regular sessoin
    public static function getPreviousRegularSessions()
    {
        $authKey = 'OE3KFIE649MRECGQ';
        //$authKey = $request->authKey;
        $url = 'https://ugr.duetbd.org/get-architecture-previous-regular-session-data';

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url . '?authKey=' . urlencode($authKey),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            return $data['sessions'] ?? null;
        }

        return null;
    }
    public static function getPreviousReviewSession()
    {
        $authKey = 'OE3KFIE649MRECGQ';
        //$authKey = $request->authKey;
        $url = 'https://ugr.duetbd.org/get-architecture-previous-review-session-data';

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url . '?authKey=' . urlencode($authKey),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            return $data['sessions'] ?? null;
        }

        return null;
    }
    public static function  getSessionWiseTheoryCourses(Request $request,$sid){
         $authKey = 'OE3KFIE649MRECGQ';
        //$authKey = $request->authKey;
        // ✅ Properly embed $sid into the URL
        $url = "https://ugr.duetbd.org/session-wise-theory-courses/{$sid}?authKey=" . urlencode($authKey);

        /*//debug
        return response()->json([
            'request' => $request->authKey,
            'sid' => $sid,
            'url'=>$url
        ]);*/

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            return $data ?? null;
        }

        return ['error' => 'Unable to fetch data', 'status_code' => $httpCode];
    }
    public static function  getSessionWiseSessionalCourses(Request $request,$sid){
        $authKey = 'OE3KFIE649MRECGQ';
        //$authKey = $request->authKey;
        // ✅ Properly embed $sid into the URL
        $url = "https://ugr.duetbd.org/session-wise-sessional-courses/{$sid}?authKey=" . urlencode($authKey);

        /*//debug
        return response()->json([
            'request' => $request->authKey,
            'sid' => $sid,
            'url'=>$url
        ]);*/

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            return $data ?? null;
        }

        return ['error' => 'Unable to fetch data', 'status_code' => $httpCode];
    }

    public static function  getSessionWiseTheorySessionalCourses(Request $request,$sid){
        $authKey = 'OE3KFIE649MRECGQ';
        //$authKey = $request->authKey;
        // ✅ Properly embed $sid into the URL
        $url = "https://ugr.duetbd.org/session-wise-theory-sessional-courses/{$sid}?authKey=" . urlencode($authKey);

        /*//debug
        return response()->json([
            'request' => $request->authKey,
            'sid' => $sid,
            'url'=>$url
        ]);*/

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            return $data ?? null;
        }

        return ['error' => 'Unable to fetch data', 'status_code' => $httpCode];
    }

    public static function  getSessionWiseStudentAdvisor(Request $request,$sid){
        $authKey = 'OE3KFIE649MRECGQ';
        //$authKey = $request->authKey;
        // ✅ Properly embed $sid into the URL
        $url = "https://ugr.duetbd.org/teacher-student/{$sid}?authKey=" . urlencode($authKey);

        /*//debug
        return response()->json([
            'request' => $request->authKey,
            'sid' => $sid,
            'url'=>$url
        ]);*/

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            return $data ?? null;
        }

        return ['error' => 'Unable to fetch data', 'status_code' => $httpCode];
    }

    public static function  getCoOrdinator(Request $request){
        $authKey = 'OE3KFIE649MRECGQ';
        //$authKey = $request->authKey;
        // ✅ Properly embed $sid into the URL
        $url = "https://ugr.duetbd.org/co-ordinator-arch?authKey=" . urlencode($authKey);

        /*//debug
        return response()->json([
            'request' => $request->authKey,
            'sid' => $sid,
            'url'=>$url
        ]);*/

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            return $data ?? null;
        }

        return ['error' => 'Unable to fetch data', 'status_code' => $httpCode];
    }

    public static function  getSessionInfo($sid){
        $authKey = 'OE3KFIE649MRECGQ';
        //$authKey = $request->authKey;
        // ✅ Properly embed $sid into the URL
        $url = "https://ugr.duetbd.org/get-seesion-info/{$sid}?authKey=" . urlencode($authKey);

        /*//debug
        return response()->json([
            'request' => $request->authKey,
            'sid' => $sid,
            'url'=>$url
        ]);*/

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            return $data ?? null;
        }

        return ['error' => 'Unable to fetch data', 'status_code' => $httpCode];
    }





}
