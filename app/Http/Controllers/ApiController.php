<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function get_previous_session(Request $request){
        $authKey = 'OE3KFIE649MRECGQ'; // raw key
        $url = 'https://ugr.duetbd.org/get-architecture-previous-session-data';

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url . '?authKey=' . urlencode($authKey),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        //return $response;
        if ($httpCode === 200) {
            $data = json_decode($response, true);
            return response()->json([
                'session' => $data['sessions'] ?? null
            ]);
        }

        return response()->json([
            'error' => 'Failed to fetch session',
            'http_code' => $httpCode,
            'response' => $response,
        ], $httpCode);
    }
}
