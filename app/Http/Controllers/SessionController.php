<?php

namespace App\Http\Controllers;

use App\Services\ApiData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    public function regular_previous_sessions()
    {
        $sessions = ApiData::getPreviousRegularSessions();
        return response()->json($sessions);
    }
    public function allRegularSessoins(){
        $sessions=ApiData::getPreviousRegularSessions();
        //here we need to show error message for not fetch data from API
        return view('Sessions.all_regular_session')->with('sessions',$sessions);
        //return response()->json($sessions);
    }

    public function allReviewSessions(){
        $data = ApiData::getPreviousReviewSession(); // could be single or multiple sessions

        // Normalize to an array of sessions
        if (isset($data['id'])) {
            // Single session (associative array) — wrap in an array
            $sessions = [$data];
        } elseif (is_array($data)) {
            // Already an array of sessions
            $sessions = $data;
        } else {
            // Unexpected response — fallback to empty
            $sessions = [];
        }

        Log::info('Normalized sessions', ['sessions' => $sessions]);

        return view('Sessions.all_review_session')->with('sessions', $sessions);
    }
}
