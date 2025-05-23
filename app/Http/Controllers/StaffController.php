<?php

namespace App\Http\Controllers;

use App\Models\RateAmount;
use App\Models\RateAssign;
use App\Models\RateHead;
use App\Models\Session;
use App\Models\Teacher;
use App\Services\ApiData;
use App\Services\LocalData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    //get regular sessions(5 sessions)  //1/1,2/1,3/1,4/1,5/1
    public function regular_previous_sessions(Request $request)
    {
        $sessions = ApiData::getPreviousRegularSessions($request);
        return response()->json($sessions);
    }

    //get review session(1 session 6/3)
    public function review_previous_sessions(Request $request)
    {
        $sessions = ApiData::getPreviousReviewSession($request);
        return response()->json($sessions);
    }

    public function session_wise_theory_courses(Request $request, $sid)
    {
        $result = ApiData::getSessionWiseTheoryCourses($request, $sid);

        return response()->json($result);
    }

    public function sessionsRegularForm(Request $request, $sid)
    {
        //return 'utsab';
        //return $sid;
        $teacher_head = Teacher::with(['user', 'designation'])
            ->whereHas('user', function ($query) {
                $query->where('is_head', 1);
            })
            ->latest()
            ->first();
        //return co-ordinator
        $teacher_coordinator = Teacher::with(['user', 'designation'])
            ->whereHas('user', function ($query) {
                $query->where('is_coordinator', 1);
            })
            ->latest()
            ->first();
        $session_info=ApiData::getSessionInfo($request,$sid);

        $teachers = Teacher::with('user', 'designation')->get();
        //all theory course with teacher
        $all_course_with_teacher = ApiData::getSessionWiseTheoryCourses($request, $sid);
        //no need to call again for class test(class test for theory course)
        // $all_course_with_class_test_teacher=ApiData::getSessionWiseTheoryCourses($request,$sid);
        //all sessional course with teacher
        $all_sessional_course_with_teacher = ApiData::getSessionWiseSessionalCourses($request, $sid);
        //all theory sessional courses
        $all_theory_sessional_courses_with_student_count = ApiData::getSessionWiseTheorySessionalCourses($request, $sid);
        //all student advisor in specific student
        $all_advisor_with_student_count = ApiData::getSessionWiseStudentAdvisor($request, $sid);
        //active coordinator(we will give it internal database)
        //$co_ordinator_arch = ApiData::getCoOrdinator($request);


         //return response()->json(['session_info'=>$session_info]);
        /*return response()->json(['head'=>$all_course_with_class_test_teacher]);*/
        return view('all_regular_session_form')
            ->with('sid',$sid)
            ->with('teacher_head', $teacher_head)
            ->with('teacher_coordinator', $teacher_coordinator)
            ->with('session_info', $session_info)
            ->with('teachers', $teachers)
            ->with('all_course_with_teacher', $all_course_with_teacher)
            ->with('all_course_with_class_test_teacher', $all_course_with_teacher)
            ->with('all_sessional_course_with_teacher', $all_sessional_course_with_teacher)
            ->with('all_theory_sessional_courses_with_student_count', $all_theory_sessional_courses_with_student_count)
            ->with('all_advisor_with_student_count', $all_advisor_with_student_count);


    }

    public function storeExaminationModerationCommittee(Request $request)
    {
        $teacherIds = $request->input('moderation_committee_teacher_ids'); // array
        $amounts = $request->input('moderation_committee_amounts');        // array (indexed)
        $sessionId = $request->sid; // You can make this dynamic
        $min_rate=$request->moderation_committee_min_rate;
        $max_rate=$request->moderation_committee_max_rate;

        Log::info('teacherId',$teacherIds);
        Log::info('teacherId',$amounts);
        Log::info('sessionId: ' . $sessionId);

        // Step 1: Validate teacher inputs
        if (empty($teacherIds) || !is_array($teacherIds) || count($teacherIds) !== count($amounts)) {
            return response()->json([
                'message' => 'Invalid data submitted. Please select teachers and their respective student count.'
            ], 422);
        }


        Log::info('pass out1');
        // Step 2: Check for duplicates
        if (count($teacherIds) !== count(array_unique($teacherIds))) {
            return response()->json([
                'message' => 'Duplicate teacher selection detected. Please choose unique teachers.'
            ], 422);
        }


        // ✅ Step 3: Check if each amount is within min and max rate
        foreach ($amounts as $index => $amount) {
            if (!is_numeric($amount)) {
                return response()->json([
                    'message' => "Invalid amount format for teacher at index {$index}."
                ], 422);
            }

            if ($amount < $min_rate || $amount > $max_rate) {
                return response()->json([
                    'message' => "Amount for teacher at position " . ($index + 1) . " must be between {$min_rate} and {$max_rate}."
                ], 422);
            }
        }


        Log::info('pass out2');
        DB::beginTransaction();

        try {
            // Step 3: Ensure RateHead exists
            $rateHead = RateHead::where('order_no', 1)->first();
            Log::info('rateHead', $rateHead ? $rateHead->toArray() : ['rateHead' => null]);
            if (!$rateHead) {
                $rateHead = new RateHead();
                $rateHead->head = 'Moderation';
                $rateHead->exam_type = 1;
                $rateHead->order_no = 1;
                $rateHead->dist_type = 'Individual';
                $rateHead->enable_min = 1;
                $rateHead->enable_max = 1;
                $rateHead->is_course = 0;
                $rateHead->is_student_count = 0;
                $rateHead->marge_with = null;
                $rateHead->status = 1;
                $rateHead->save();
                if ($rateHead->save()) {
                    Log::info('✅ New RateHead created', $rateHead->toArray());
                } else {
                    Log::error('❌ Failed to save RateHead');
                }
            }

            //ensure session exist
            $session_info = LocalData::getOrCreateSession($sessionId);


            // Step 4: Ensure  RateAmount exists(Rate Amount Exist for Rate Head=1)
            $rateAmount = RateAmount::where('rate_head_id', $rateHead->id)
                ->where('session_id', $sessionId)
                ->first();

            Log::info('rateAmount', $rateAmount ? $rateAmount->toArray() : ['$rateAmount' => null]);
            if (!$rateAmount) {
                $rateAmount = new RateAmount();
                $rateAmount->default_rate = 0;
                $rateAmount->min_rate = $min_rate;
                $rateAmount->max_rate = $max_rate;
                $rateAmount->session_id = $session_info->id;
                $rateAmount->rate_head_id = $rateHead->id;
                $rateAmount->save();
                if ($rateAmount->save()) {
                    Log::info('✅ New RateHead created', $rateAmount->toArray());
                } else {
                    Log::error('❌ Failed to save RateHead');
                }
            }

            // Step 5: Loop and store teacher-wise rate_assign
            foreach ($teacherIds as $index => $teacherId) {
                $amount = isset($amounts[$index]) ? intval($amounts[$index]) : 0;

                if ($amount <= 0) {
                  //  DB::rollBack();
                    return response()->json([
                        'message' => "Invalid amount for teacher ID: $teacherId."
                    ], 422);
                }

                RateAssign::create([
                    'teacher_id' => $teacherId,
                    'rate_head_id' => $rateHead->id,
                    'session_id' => $session_info->id,
                    'no_of_items' => 0,
                    'total_amount' => $amount,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Moderation committee data stored successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function storeExaminerPaperSetter(Request $request)
    {
        $paperSetterData = $request->input('paper_setter_ids', []);
        $examinerData = $request->input('examiner_ids', []);
        $noOfScripts = $request->input('no_of_script', []);
        $script_rate=$request->examiner_rate_per_script;
        $examiner_min_rate=$request->examiner_min_rate;
        $paper_setter_rate=$request->paper_setter_rate;
        $sessionId = $request->sid;

        try {
            DB::beginTransaction();

            // RateHead 2 - Paper Setters
            $rateHead_2 = RateHead::where('order_no', 2)->first();
            if (!$rateHead_2) {
                $rateHead_2 = new RateHead();
                $rateHead_2->head = 'Paper Setters';
                $rateHead_2->exam_type = 1;
                $rateHead_2->order_no = 2;
                $rateHead_2->dist_type = 'Individual';
                $rateHead_2->enable_min = 0;
                $rateHead_2->enable_max = 0;
                $rateHead_2->is_course = 1;
                $rateHead_2->is_student_count = 0;
                $rateHead_2->marge_with = null;
                $rateHead_2->status = 1;
                $rateHead_2->save();
                Log::info('✅ New RateHead created', $rateHead_2->toArray());
            }

            // RateHead 3 - Examiner
            $rateHead_3 = RateHead::where('order_no', 3)->first();
            if (!$rateHead_3) {
                $rateHead_3 = new RateHead();
                $rateHead_3->head = 'Examiner';
                $rateHead_3->exam_type = 1;
                $rateHead_3->order_no = 3;
                $rateHead_3->dist_type = 'Share';
                $rateHead_3->enable_min = 1;
                $rateHead_3->enable_max = 0;
                $rateHead_3->is_course = 1;
                $rateHead_3->is_student_count = 1;
                $rateHead_3->marge_with = null;
                $rateHead_3->status = 1;
                $rateHead_3->save();
                Log::info('✅ New RateHead created', $rateHead_3->toArray());
            }

            // Ensure Session exists
            $session_info = LocalData::getOrCreateSession($sessionId);

            // RateAmount for RateHead 2
            $rateAmount_2 = RateAmount::where('rate_head_id', $rateHead_2->id)
                ->where('session_id', $session_info->id)
                ->first();

            if (!$rateAmount_2) {
                $rateAmount_2 = new RateAmount();
                $rateAmount_2->rate_head_id = $rateHead_2->id;
                $rateAmount_2->session_id = $session_info->id;
                $rateAmount_2->default_rate = $paper_setter_rate;
                $rateAmount_2->save();
                Log::info('✅ New RateAmount created', $rateAmount_2->toArray());
            }

            // RateAmount for RateHead 3
            $rateAmount_3 = RateAmount::where('rate_head_id', $rateHead_3->id)
                ->where('session_id', $session_info->id)
                ->first();

            if (!$rateAmount_3) {
                $rateAmount_3 = new RateAmount();
                $rateAmount_3->rate_head_id = $rateHead_3->id;
                $rateAmount_3->session_id = $session_info->id;
                $rateAmount_3->default_rate = $script_rate;
                $rateAmount_3->min_rate = $examiner_min_rate;
                $rateAmount_3->save();
                Log::info('✅ New RateAmount created', $rateAmount_3->toArray());
            }


            /*"paper_setters":
               {
                    "1": ["110", "120"],
                    "4": ["120", "140"],
                }*/
            //here $courseId is 1,4
            //$teacherIds [110, 120] for 1
            //$teacherIds [120, 140] for 4
            // Store Paper Setters
            foreach ($paperSetterData as $courseId => $teacherIds) {
                //loop for $teacherIds [120, 140] $teacherId=120,$teacherId=140
                foreach ($teacherIds as $teacherId) {
                    $rateAssign = new RateAssign();
                    $rateAssign->teacher_id = $teacherId;
                    $rateAssign->rate_head_id = $rateHead_2->id;
                    $rateAssign->session_id = $session_info->id;
                    $rateAssign->no_of_items = 0;
                    $rateAssign->total_amount = $paper_setter_rate;
                    $rateAssign->save();
                }
            }

            // Store Examiners
            foreach ($examinerData as $courseId => $teacherIds) {
                $no_of_scripts = $noOfScripts[$courseId] ?? 0;
                $teacherCount = count($teacherIds);

                if ($teacherCount > 0) {
                    $no_of_scripts = $no_of_scripts / $teacherCount;
                } else {
                    $no_of_scripts = 0;
                }
                foreach ($teacherIds as $teacherId) {
                    $total_amount = $no_of_scripts * $rateAmount_3->default_rate;
                    if ($total_amount < $rateAmount_3->min_rate) {
                        $total_amount = $rateAmount_3->min_rate;
                    }
                    //another way for insert
                    RateAssign::create([
                        'teacher_id'   => $teacherId,
                        'rate_head_id' => $rateHead_3->id,
                        'session_id'   => $session_info->id,
                        'no_of_items'  => $no_of_scripts,
                        'total_amount' => $total_amount,
                    ]);
                }
            }

            DB::commit();
            Log::info('✅ All examiner and paper setter data saved successfully.', [
                'session_id' => $session_info->id,
                'rate_heads' => [
                    'paper_setter' => $rateHead_2->id,
                    'examiner' => $rateHead_3->id,
                ]
            ]);


            return response()->json([
                'message' => 'Examiner and Paper Setter data saved successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while saving data.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    public function storeClassTestTeacherStore(Request $request)
    {
        $classTestTeacherData = $request->input('class_test_teachers_ids', []);
        $noOfStudents = $request->input('no_of_students_ct', []);
        $sessionId = $request->sid;
        $class_test_rate = $request->class_test_rate;
        try {
            DB::beginTransaction();

            // RateHead 3 - Examineer
            $rateHead = RateHead::where('order_no', 4)->first();
            if (!$rateHead) {
                $rateHead = new RateHead();
                $rateHead->head = 'Class Test';
                $rateHead->exam_type = 1;
                $rateHead->order_no = 4;
                $rateHead->dist_type = 'Share';
                $rateHead->enable_min = 0;
                $rateHead->enable_max = 0;
                $rateHead->is_course = 1;
                $rateHead->is_student_count = 1;
                $rateHead->marge_with = null;
                $rateHead->status = 1;
                $rateHead->save();
                Log::info('✅ New RateHead created', $rateHead->toArray());
            }


            // Ensure Session exists
            $session_info = LocalData::getOrCreateSession($sessionId);

            // RateAmount for RateHead 2
            $rateAmount = RateAmount::where('rate_head_id', $rateHead->id)
                ->where('session_id', $session_info->id)
                ->first();

            if (!$rateAmount) {
                $rateAmount = new RateAmount();
                $rateAmount->rate_head_id = $rateHead->id;
                $rateAmount->session_id = $session_info->id;
                $rateAmount->default_rate = $class_test_rate;
                $rateAmount->save();
                Log::info('✅ New RateAmount created', $rateAmount->toArray());
            }


            /*"paper_setters":
               {
                    "1": ["110", "120"],
                    "4": ["120", "140"],
                }*/
            //here $courseId is 1,4
            //$teacherIds [110, 120] for 1
            //$teacherIds [120, 140] for 4
            // Store Paper Setters

            // Store Examiners
            foreach ($classTestTeacherData as $courseId => $teacherIds) {
                $studentCount = $noOfStudents[$courseId] ?? 0;
                $teacherCount = count($teacherIds);

                if ($teacherCount > 0) {
                   // $studentCount = $studentCount / $teacherCount;
                    //2 class test taken that's why multiply by 2
                    $studentCount = $studentCount *2;
                } else {
                    $studentCount = 0;
                }
                foreach ($teacherIds as $teacherId) {
                    $total_amount = $studentCount * $rateAmount->default_rate;

                    //another way for insert
                    RateAssign::create([
                        'teacher_id'   => $teacherId,
                        'rate_head_id' => $rateHead->id,
                        'session_id'   => $session_info->id,
                        'no_of_items'  => $studentCount,
                        'total_amount' => $total_amount,
                    ]);
                }
            }

            DB::commit();
            Log::info('✅ Class Test Teacher Data Stored Successfully.', [
                'session_id' => $session_info->id,
                'rate_heads' => [
                    'class test teacher' => $rateHead->id,
                ]
            ]);


            return response()->json([
                'message' => 'Class Test Teacher data saved successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while saving data.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function storeSessionalCourseTeacher(Request $request)
    {


        $sessionalTeacherData = $request->input('sessional_course_teacher_ids', []);
        $noOfContactHour = $request->input('no_of_contact_hour', []);
        $total_week=$request->input('total_week');
        $sessionId = $request->sid;
        $sessional_per_hour_rate = $request->sessional_per_hour_rate;
        $sessional_examiner_min_rate = $request->sessional_examiner_min_rate;


        try {
            DB::beginTransaction();

            // Step 1: Create or fetch RateHead
            $rateHead = RateHead::where('order_no', 5)->first();
            if (!$rateHead) {
                $rateHead = new RateHead();
                $rateHead->head = 'Laboratory/Survey works';
                $rateHead->exam_type = 1;
                $rateHead->order_no = 5;
                $rateHead->dist_type = 'Share';
                $rateHead->enable_min = 1;
                $rateHead->enable_max = 0;
                $rateHead->is_course = 1;
                $rateHead->is_student_count = 0;
                $rateHead->marge_with = null;
                $rateHead->status = 1;
                $rateHead->save();

                Log::info('✅ New RateHead created', $rateHead->toArray());
            }

            // Step 2: Get or create session
            $session_info = LocalData::getOrCreateSession($sessionId);

            // Step 3: Get or create RateAmount
            $rateAmount = RateAmount::where('rate_head_id', $rateHead->id)
                ->where('session_id', $session_info->id)
                ->first();

            if (!$rateAmount) {
                $rateAmount = new RateAmount();
                $rateAmount->rate_head_id = $rateHead->id;
                $rateAmount->session_id = $session_info->id;
                $rateAmount->default_rate = $sessional_per_hour_rate; // Set your desired rate per hour
                $rateAmount->min_rate = $sessional_examiner_min_rate;
                $rateAmount->save();

                Log::info('✅ New RateAmount created', $rateAmount->toArray());
            }

            /*{
               "$sessionalTeacherData": {
                       "2": ["301", "288", "165", "164"],
                       "3": ["169", "163"]
               },
                "no_of_contact_hour": {
                       "2": ["46", "5", "95", "71"],
                       "3": ["10", "45"]
             }
           }*/
            // Step 4: Store RateAssign for each teacher
            foreach ($sessionalTeacherData as $courseId => $teacherIds) {
                $hours = $noOfContactHour[$courseId] ?? [];

                foreach ($teacherIds as $index => $teacherId) {
                    $contactHour = isset($hours[$index]) ? floatval($hours[$index]) : 0;
                    $totalAmount = $contactHour * $rateAmount->default_rate * $total_week;
                    // Apply minimum amount logic
                    if ($totalAmount < $rateAmount->min_rate) {
                        $totalAmount = $rateAmount->min_rate;
                    }

                    $assign = new RateAssign();
                    $assign->teacher_id = $teacherId;
                    $assign->rate_head_id = $rateHead->id;
                    $assign->session_id = $session_info->id;
                    $assign->no_of_items = $contactHour;
                    $assign->total_amount = $totalAmount;
                    $assign->save();
                }
            }

            DB::commit();
            Log::info('✅ All Sessional Course Teacher data saved successfully.', [
                'session_id' => $session_info->id,
                'rate_head' => $rateHead->id,
            ]);

            return response()->json([
                'message' => 'Sessional Course Teacher data saved successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while saving data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function storeScrutinizers(Request $request)
    {
        $scrutinizer_teacher_ids = $request->input('scrutinizer_teacher_ids', []);
        $scrutinizers_no_of_students = $request->input('scrutinizers_no_of_students', []);
        $sessionId=$request->input('sid');
        $scrutinize_script_rate=$request->input('scrutinize_script_rate');
        $scrutinize_min_rate=$request->input('scrutinize_min_rate');

        Log::info('Scrutinizer Form Submission Data:', [
            'scrutinizer_teacher_ids' => $scrutinizer_teacher_ids,
            'scrutinizers_no_of_students' => $scrutinizers_no_of_students
        ]);

        $errors = [];

        // Validation
        if (empty($scrutinizer_teacher_ids)) {
            $errors['scrutinizer_teacher_ids'] = 'You must select at least one teacher.';
        }

        if (empty($scrutinizers_no_of_students)) {
            $errors['scrutinizers_no_of_students'] = 'You must provide number of students.';
        }

        foreach ($scrutinizer_teacher_ids as $courseId => $teacherIds) {
            if (empty($teacherIds)) {
                $errors["scrutinizer_teacher_ids.$courseId"] = "Select at least one teacher for course ID $courseId.";
            }

            $studentCount = $scrutinizers_no_of_students[$courseId] ?? null;
            if ($studentCount === null || $studentCount === '' || $studentCount < 1) {
                $errors["scrutinizers_no_of_students.$courseId"] = "Enter a valid number of students for course ID $courseId.";
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $errors
            ], 422);
        }

        try {
            DB::beginTransaction();

            // 1️⃣ Manually fetch or create RateHead (object-based)
            $rateHead = RateHead::where('order_no', 9)->first(); // Use appropriate order_no for Scrutinizer
            if (!$rateHead) {
                $rateHead = new RateHead();
                $rateHead->head = 'Scrutinizer';
                $rateHead->exam_type = 1;
                $rateHead->order_no = 9;
                $rateHead->dist_type = 'Share';
                $rateHead->enable_min = 1;
                $rateHead->enable_max = 0;
                $rateHead->is_course = 1;
                $rateHead->is_student_count = 1;
                $rateHead->marge_with = null;
                $rateHead->status = 1;
                $rateHead->save();
                Log::info('✅ RateHead created (Scrutinizer):', $rateHead->toArray());
            }

            // 2️⃣ Ensure Session
            $session_info = LocalData::getOrCreateSession($sessionId);

            // 3️⃣ Manually fetch or create RateAmount (object-based)
            $rateAmount = RateAmount::where('rate_head_id', $rateHead->id)
                ->where('session_id', $session_info->id)
                ->first();

            if (!$rateAmount) {
                $rateAmount = new RateAmount();
                $rateAmount->rate_head_id = $rateHead->id;
                $rateAmount->session_id = $session_info->id;
                $rateAmount->default_rate = $scrutinize_script_rate;  // ₹24 per script
                $rateAmount->min_rate = $scrutinize_min_rate;    // ₹1000 minimum
                $rateAmount->save();
                Log::info('✅ RateAmount created (Scrutinizer):', $rateAmount->toArray());
            }

            // 4️⃣ Save RateAssigns
            foreach ($scrutinizer_teacher_ids as $courseId => $teacherIds) {
                $studentCount = (int) $scrutinizers_no_of_students[$courseId];
                $teacherCount = count($teacherIds);

                if ($teacherCount > 0 && $studentCount > 0) {
                    $studentsPerTeacher = $studentCount / $teacherCount;

                    foreach ($teacherIds as $teacherId) {
                        $calculatedAmount = $studentsPerTeacher * $rateAmount->default_rate;
                        $total_amount = max($rateAmount->min_rate, $calculatedAmount); // Enforce min

                        RateAssign::create([
                            'teacher_id'   => $teacherId,
                            'rate_head_id' => $rateHead->id,
                            'session_id'   => $session_info->id,
                            'no_of_items'  => $studentsPerTeacher,
                            'total_amount' => $total_amount,
                        ]);
                    }
                }
            }

            DB::commit();

            Log::info('✅ Scrutinizer data saved.', [
                'session_id' => $session_info->id,
                'rate_head_id' => $rateHead->id
            ]);

            return response()->json([
                'message' => 'Scrutinizers committee saved successfully.',
                'scrutinizer_teacher_ids' => $scrutinizer_teacher_ids,
                'scrutinizers_no_of_students' => $scrutinizers_no_of_students,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ Error saving scrutinizer data:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'An error occurred while saving data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function storeTheoryGradeSheet(Request $request)
    {
        $teacherData = $request->input('prepare_theory_grade_sheet_teacher_ids', []);
        $studentData = $request->input('prepare_theory_grade_sheet_no_of_students', []);
        $sessionId=$request->sid;
        $theory_grade_sheet_rate=$request->theory_grade_sheet_rate;

        Log::info('Received Theory Grade Sheet Submission', [
            'session_id' => $sessionId,
            'teacher_data' => $teacherData,
            'student_data' => $studentData
        ]);
        $errors = [];

        // ✅ Step 1: Basic validation
        if (empty($teacherData)) {
            $errors['prepare_theory_grade_sheet_teacher_ids'] = 'You must select at least one teacher.';
        }

        if (empty($studentData)) {
            $errors['prepare_theory_grade_sheet_no_of_students'] = 'You must provide number of students.';
        }


        foreach ($teacherData as $courseId => $teacherIds) {
            if (empty($teacherIds)) {
                $errors["teacher_ids.$courseId"] = "Select at least one teacher for course ID $courseId.";
            }

            $studentCount = $studentData[$courseId] ?? null;
            if ($studentCount === null || $studentCount === '' || $studentCount < 1) {
                $errors["no_of_students.$courseId"] = "Enter a valid number of students for course ID $courseId.";
            }
        }


        if (!empty($errors)) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $errors
            ], 422);
        }

        try {
            DB::beginTransaction();

            // ✅ Step 2: RateHead creation
            $rateHead = RateHead::where('order_no', '8.a')->first();

            if (!$rateHead) {
                $rateHead = new RateHead();
                $rateHead->order_no = '8.a';
                $rateHead->head = 'Gradesheet Preparation';
                $rateHead->sub_head = 'Theoretical';
                $rateHead->exam_type = 1;
                $rateHead->dist_type = 'Share';
                $rateHead->enable_min = 0;
                $rateHead->enable_max = 0;
                $rateHead->is_course = 1;
                $rateHead->is_student_count = 1;
                $rateHead->marge_with = null;
                $rateHead->status = 1;
                $rateHead->save();
                Log::info('✅ New RateHead created:', $rateHead->toArray());
            }

            // ✅ Step 3: Ensure Session exists
            $session_info = LocalData::getOrCreateSession($sessionId); // adjust as needed

            // ✅ Step 4: RateAmount
            $rateAmount = RateAmount::where('rate_head_id', $rateHead->id)
                ->where('session_id', $session_info->id)
                ->first();

            if (!$rateAmount) {
                $rateAmount = new RateAmount();
                $rateAmount->rate_head_id = $rateHead->id;
                $rateAmount->session_id = $session_info->id;
                $rateAmount->default_rate = $theory_grade_sheet_rate;  // ₹24 per script (example rate)
                $rateAmount->save();

                Log::info('✅ RateAmount created (Scrutinizer):', $rateAmount->toArray());
            }


            foreach ($teacherData as $courseId => $teacherIds) {
                $studentCount = (int) $studentData[$courseId];
                $teacherCount = count($teacherIds);

                if ($teacherCount > 0 && $studentCount > 0) {
                    $studentsPerTeacher = $studentCount / $teacherCount;

                    foreach ($teacherIds as $teacherId) {
                        $calculatedAmount = $studentsPerTeacher * $rateAmount->default_rate;
                        //$total_amount = max($rateAmount->min_rate, $calculatedAmount); // Enforce min

                        RateAssign::create([
                            'teacher_id'   => $teacherId,
                            'rate_head_id' => $rateHead->id,
                            'session_id'   => $session_info->id,
                            'no_of_items'  => $studentsPerTeacher,
                            'total_amount' => $calculatedAmount,
                        ]);
                    }
                }
            }


            DB::commit();

            Log::info('✅ Theory Grade Sheet Rate Assignments saved.', [
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            return response()->json([
                'message' => 'Theory Grade Sheet committee saved successfully.',
                'grade_sheet_teacher_ids' => $teacherData,
                'grade_sheet_no_of_students' => $studentData
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error saving Theory Grade Sheet data: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while saving Theory Grade Sheet data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function storeSessionalGradeSheet(Request $request)
    {
        $teacherData = $request->input('prepare_sessional_grade_sheet_teacher_ids', []);
        $studentData = $request->input('prepare_sessional_grade_sheet_no_of_students', []);
        $sessionId   = $request->sid;
        $sessional_grade_sheet_rate   = $request->sessional_grade_sheet_rate;

        Log::info('📥 Received Sessional Grade Sheet Submission', [
            'session_id' => $sessionId,
            'teacher_data' => $teacherData,
            'student_data' => $studentData
        ]);

        $errors = [];

        // ✅ Basic validation
        if (empty($teacherData)) {
            $errors['prepare_sessional_grade_sheet_teacher_ids'] = 'You must select at least one teacher.';
        }

        if (empty($studentData)) {
            $errors['prepare_sessional_grade_sheet_no_of_students'] = 'You must provide number of students.';
        }

        foreach ($teacherData as $courseId => $teacherIds) {
            if (empty($teacherIds)) {
                $errors["teacher_ids.$courseId"] = "Select at least one teacher for course ID $courseId.";
            }

            $studentCount = $studentData[$courseId] ?? null;
            if ($studentCount === null || $studentCount === '' || $studentCount < 1) {
                $errors["no_of_students.$courseId"] = "Enter a valid number of students for course ID $courseId.";
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $errors
            ], 422);
        }

        try {
            DB::beginTransaction();

            // ✅ Step 1: Create or fetch RateHead
            $rateHead = RateHead::where('order_no', '8.b')->where('sub_head', 'Sessional')->first();
            if (!$rateHead) {
                $rateHead = new RateHead();
                $rateHead->order_no = '8.b';
                $rateHead->head = 'Gradesheet Preparation';
                $rateHead->sub_head = 'Sessional';
                $rateHead->exam_type = 1;
                $rateHead->dist_type = 'Share';
                $rateHead->enable_min = 0;
                $rateHead->enable_max = 0;
                $rateHead->is_course = 1;
                $rateHead->is_student_count = 1;
                $rateHead->marge_with = null;
                $rateHead->status = 1;
                $rateHead->save();

                Log::info('✅ New RateHead created:', $rateHead->toArray());
            }

            if ($rateHead->wasRecentlyCreated) {
                Log::info('✅ New RateHead created:', $rateHead->toArray());
            }

            // ✅ Ensure Session exists
            $session_info = LocalData::getOrCreateSession($sessionId);

            // ✅ Step 3: Create or fetch RateAmount(need to work)
            $rateAmount = RateAmount::where('rate_head_id', $rateHead->id)
                ->where('session_id', $session_info->id)
                ->first();

            if (!$rateAmount) {
                $rateAmount = new RateAmount();
                $rateAmount->rate_head_id = $rateHead->id;
                $rateAmount->session_id = $session_info->id;
                $rateAmount->default_rate = $sessional_grade_sheet_rate; // Example rate
                $rateAmount->save();

                Log::info('✅ New RateAmount created (Sessional):', $rateAmount->toArray());
            }

            // ✅ Loop through course-wise teacher assignments
            foreach ($teacherData as $courseId => $teacherIds) {
                $studentCount = (int) $studentData[$courseId];
                $teacherCount = count($teacherIds);

                if ($teacherCount > 0 && $studentCount > 0) {
                    $studentsPerTeacher = $studentCount / $teacherCount;

                    foreach ($teacherIds as $teacherId) {
                        $calculatedAmount = $studentsPerTeacher * $rateAmount->default_rate;

                        RateAssign::create([
                            'teacher_id'   => $teacherId,
                            'rate_head_id' => $rateHead->id,
                            'session_id'   => $session_info->id,
                            'no_of_items'  => $studentsPerTeacher,
                            'total_amount' => $calculatedAmount,
                        ]);
                    }
                }
            }

            DB::commit();

            Log::info('✅ Sessional Grade Sheet Rate Assignments saved.', [
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            return response()->json([
                'message' => 'Sessional Grade Sheet committee saved successfully.',
                'grade_sheet_teacher_ids' => $teacherData,
                'grade_sheet_no_of_students' => $studentData
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error saving Sessional Grade Sheet data: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while saving Sessional Grade Sheet data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function storeScrutinizersTheoryGradeSheet(Request $request)
    {
        $teacherData = $request->input('scrutinizing_theory_grade_sheet_teacher_ids', []);
        $studentData = $request->input('scrutinizing_theory_grade_sheet_no_of_students', []);
        $sessionId = $request->sid;
        $scrutinize_theory_grade_sheet_rate = $request->scrutinize_theory_grade_sheet_rate;

        Log::info('📥 Received Scrutinizing Theory Grade Sheet', [
            'session_id' => $sessionId,
            'teacher_data' => $teacherData,
            'student_data' => $studentData,
        ]);

        $errors = [];

        // Step 1: Validation
        if (empty($teacherData)) {
            $errors['scrutinizing_theory_grade_sheet_teacher_ids'] = 'You must select at least one teacher.';
        }

        if (empty($studentData)) {
            $errors['scrutinizing_theory_grade_sheet_no_of_students'] = 'You must provide the number of students.';
        }

        foreach ($teacherData as $courseId => $teacherIds) {
            if (empty($teacherIds)) {
                $errors["teacher_ids.$courseId"] = "Select at least one teacher for course ID $courseId.";
            }

            $studentCount = $studentData[$courseId] ?? null;
            if ($studentCount === null || $studentCount === '' || $studentCount < 1) {
                $errors["no_of_students.$courseId"] = "Enter a valid number of students for course ID $courseId.";
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $errors
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Step 2: Create or fetch RateHead
            $rateHead = RateHead::where('order_no', '10.a')->first();
            if (!$rateHead) {
                $rateHead = new RateHead();
                $rateHead->head = 'Gradesheet Scrutinizing';
                $rateHead->order_no = '10.a';
                $rateHead->sub_head = 'Theoretical';
                $rateHead->exam_type = 1;
                $rateHead->dist_type = 'Share';
                $rateHead->enable_min = 0;
                $rateHead->enable_max = 0;
                $rateHead->is_course = 1;
                $rateHead->is_student_count = 1;
                $rateHead->marge_with = null;
                $rateHead->status = 1;
                $rateHead->save();

                Log::info('✅ RateHead created or found:', $rateHead->toArray());
            }

            // Step 3: Ensure session
            $session_info = LocalData::getOrCreateSession($sessionId);

            // Step 4: Create or fetch RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = $scrutinize_theory_grade_sheet_rate;
                $rateAmount->save();
                Log::info('✅ RateAmount created:', $rateAmount->toArray());
            }

            // Step 5: RateAssign per teacher
            foreach ($teacherData as $courseId => $teacherIds) {
                $studentCount = (int) $studentData[$courseId];
                $teacherCount = count($teacherIds);

                if ($teacherCount > 0 && $studentCount > 0) {
                    $studentsPerTeacher = $studentCount / $teacherCount;

                    foreach ($teacherIds as $teacherId) {
                        $calculatedAmount = $studentsPerTeacher * $rateAmount->default_rate;
                        //$totalAmount = max($rateAmount->min_rate ?? 0, $calculatedAmount); // Enforce min

                        $rateAssign = new RateAssign();
                        $rateAssign->teacher_id = $teacherId;
                        $rateAssign->rate_head_id = $rateHead->id;
                        $rateAssign->session_id = $session_info->id;
                        $rateAssign->no_of_items = $studentsPerTeacher;
                        $rateAssign->total_amount = $calculatedAmount;
                        $rateAssign->save();
                    }
                }
            }

            DB::commit();

            Log::info('✅ Scrutinizer (Theory) Rate Assignments saved.', [
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            return response()->json([
                'message' => 'Scrutinizer (Theory) Grade Sheet committee saved successfully.',
                'teacher_ids' => $teacherData,
                'student_counts' => $studentData,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error saving Scrutinizer (Theory) Grade Sheet: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error occurred while saving data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function storeScrutinizersSessionalGradeSheet(Request $request)
    {
        $teacherData = $request->input('scrutinizing_sessional_grade_sheet_teacher_ids', []);
        $studentData = $request->input('scrutinizing_sessional_grade_sheet_no_of_students', []);
        $sessionId = $request->sid;
        $scrutinize_sessional_grade_sheet_rate = $request->scrutinize_sessional_grade_sheet_rate;

        Log::info('📥 Received Scrutinizing Sessional Grade Sheet', [
            'session_id' => $sessionId,
            'teacher_data' => $teacherData,
            'student_data' => $studentData,
        ]);

        $errors = [];

        // Step 1: Validation
        if (empty($teacherData)) {
            $errors['scrutinizing_sessional_grade_sheet_teacher_ids'] = 'You must select at least one teacher.';
        }

        if (empty($studentData)) {
            $errors['scrutinizing_sessional_grade_sheet_no_of_students'] = 'You must provide number of students.';
        }

        foreach ($teacherData as $courseId => $teacherIds) {
            if (empty($teacherIds)) {
                $errors["teacher_ids.$courseId"] = "Select at least one teacher for course ID $courseId.";
            }

            $studentCount = $studentData[$courseId] ?? null;
            if ($studentCount === null || $studentCount === '' || $studentCount < 1) {
                $errors["no_of_students.$courseId"] = "Enter a valid number of students for course ID $courseId.";
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $errors
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Step 2: Create or fetch RateHead
            $rateHead = RateHead::firstOrNew([
                'order_no' => '10.b',
            ]);

            $rateHead->head = 'GradeSheet Scrutinizing (Sessional)';
            $rateHead->sub_head = 'Sessional';
            $rateHead->order_no='10.b';
            $rateHead->exam_type = 1;
            $rateHead->dist_type = 'Share';
            $rateHead->enable_min = 0;
            $rateHead->enable_max = 0;
            $rateHead->is_course = 1;
            $rateHead->is_student_count = 1;
            $rateHead->marge_with = null;
            $rateHead->status = 1;
            $rateHead->save();

            Log::info('✅ RateHead created or found:', $rateHead->toArray());

            // Step 3: Ensure session
            $session_info = LocalData::getOrCreateSession($sessionId);

            // Step 4: Create or fetch RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = $scrutinize_sessional_grade_sheet_rate; // Example rate
                $rateAmount->save();
                Log::info('✅ RateAmount created:', $rateAmount->toArray());
            }

            // Step 5: Assign rates per teacher
            foreach ($teacherData as $courseId => $teacherIds) {
                $studentCount = (int) $studentData[$courseId];
                $teacherCount = count($teacherIds);

                if ($teacherCount > 0 && $studentCount > 0) {
                    $studentsPerTeacher = $studentCount / $teacherCount;

                    foreach ($teacherIds as $teacherId) {
                        $calculatedAmount = $studentsPerTeacher * $rateAmount->default_rate;
                       // $totalAmount = max($rateAmount->min_rate ?? 0, $calculatedAmount); // Enforce min

                        $rateAssign = new RateAssign();
                        $rateAssign->teacher_id = $teacherId;
                        $rateAssign->rate_head_id = $rateHead->id;
                        $rateAssign->session_id = $session_info->id;
                        $rateAssign->no_of_items = $studentsPerTeacher;
                        $rateAssign->total_amount = $calculatedAmount;
                        $rateAssign->save();
                    }
                }
            }

            DB::commit();

            Log::info('✅ Scrutinizer (Sessional) Rate Assignments saved.', [
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            return response()->json([
                'message' => 'Scrutinizer (Sessional) Grade Sheet saved successfully.',
                'teacher_ids' => $teacherData,
                'student_counts' => $studentData,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error saving Scrutinizer (Sessional) Grade Sheet: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while saving data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function storePreparedComputerizedResult(Request $request)
    {
        $teacherData = $request->input('prepared_computerized_result_teacher_ids', []);
        $studentData = $request->input('prepared_computerized_result_no_of_students', []);
        $sessionId = $request->sid;
        $prepare_computerized_result_rate=$request->input('prepare_computerized_result_rate');

        Log::info('📥 Received Prepared Computerized Result Data', [
            'session_id' => $sessionId,
            'teacher_data' => $teacherData,
            'student_data' => $studentData,
        ]);

        $errors = [];

        // Step 1: Validate teacher and student input
        if (empty($teacherData)) {
            $errors['prepared_computerized_result_teacher_ids'] = 'You must select at least one teacher.';
        }

        if (empty($studentData)) {
            $errors['prepared_computerized_result_no_of_students'] = 'You must provide the number of students.';
        }

        foreach ($teacherData as $courseId => $teacherIds) {
            if (empty($teacherIds)) {
                $errors["teacher_ids.$courseId"] = "Select at least one teacher for course ID $courseId.";
            }

            $studentCount = $studentData[$courseId] ?? null;
            if ($studentCount === null || $studentCount === '' || $studentCount < 1) {
                $errors["no_of_students.$courseId"] = "Enter a valid number of students for course ID $courseId.";
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $errors
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Step 2: Create or fetch RateHead
            $rateHead = RateHead::firstOrNew([
                'order_no' => '8.d',
            ]);

            $rateHead->head = 'Prepared Computerized Result';
            $rateHead->exam_type = 1;
            $rateHead->dist_type = 'Share';
            $rateHead->is_course = 1;
            $rateHead->is_student_count = 1;
            $rateHead->marge_with = null;
            $rateHead->status = 1;
            $rateHead->save();

            Log::info('✅ RateHead created or found:', $rateHead->toArray());

            // Step 3: Get or create session
            $session_info = LocalData::getOrCreateSession($sessionId);

            // Step 4: Create or fetch RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = $prepare_computerized_result_rate; // Default rate for computerized result
                $rateAmount->save();
                Log::info('✅ RateAmount created:', $rateAmount->toArray());
            }

            // Step 5: Assign to teachers
            foreach ($teacherData as $courseId => $teacherIds) {
                $studentCount = (int) $studentData[$courseId];
                $teacherCount = count($teacherIds);

                if ($teacherCount > 0 && $studentCount > 0) {
                    $studentsPerTeacher = $studentCount / $teacherCount;

                    foreach ($teacherIds as $teacherId) {
                        $calculatedAmount = $studentsPerTeacher * $rateAmount->default_rate;
                       // $totalAmount = max($rateAmount->min_rate ?? 0, $calculatedAmount);

                        $rateAssign = new RateAssign();
                        $rateAssign->teacher_id = $teacherId;
                        $rateAssign->rate_head_id = $rateHead->id;
                        $rateAssign->session_id = $session_info->id;
                        $rateAssign->no_of_items = $studentsPerTeacher;
                        $rateAssign->total_amount = $calculatedAmount;
                        $rateAssign->save();
                    }
                }
            }

            DB::commit();

            Log::info('✅ Prepared Computerized Result Assignments saved.', [
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            return response()->json([
                'message' => 'Prepared Computerized Result saved successfully.',
                'teacher_ids' => $teacherData,
                'student_counts' => $studentData,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error saving Prepared Computerized Result: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while saving data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function storeVerifiedComputerizedResult(Request $request)
    {
        $teacherIds = $request->input('verified_computerized_result_teachers', []);
        $totalStudents = (int) $request->input('verified_computerized_result_total_students');
        $sessionId = $request->sid;
        $verified_computerized_grade_sheet_rate = $request->verified_computerized_grade_sheet_rate;

        Log::info('📥 Received Verified Computerized Result Data', [
            'session_id' => $sessionId,
            'teacher_ids' => $teacherIds,
            'total_students' => $totalStudents,
        ]);

        $errors = [];

        // Validation
        if (empty($teacherIds)) {
            $errors['verified_computerized_result_teachers'] = 'Select at least one teacher.';
        }

        if (!$totalStudents || $totalStudents < 1) {
            $errors['verified_computerized_result_total_students'] = 'Enter a valid number of students.';
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $errors
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Step 1: RateHead
            $rateHead = RateHead::firstOrNew([
                'order_no' => '8.c',
            ]);

            if(!$rateHead->exist){
                $rateHead->head = 'Grade Sheeets/GPA Verification';
                $rateHead->exam_type = 1;
                $rateHead->dist_type = 'Share';
                $rateHead->enable_min = 0;
                $rateHead->enable_max = 0;
                $rateHead->is_course = 0;
                $rateHead->is_student_count = 1;
                $rateHead->marge_with = null;
                $rateHead->status = 1;
                $rateHead->save();
            }


            Log::info('✅ RateHead created or updated.', $rateHead->toArray());

            // Step 2: Get or create session
            $session_info = LocalData::getOrCreateSession($sessionId);

            // Step 3: RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = $verified_computerized_grade_sheet_rate; // Set your rate here
                $rateAmount->save();
                Log::info('✅ RateAmount created.', $rateAmount->toArray());
            }

            // Step 4: Assign to teachers
            $studentsPerTeacher = $totalStudents / count($teacherIds);

            foreach ($teacherIds as $teacherId) {
                $calculatedAmount = $studentsPerTeacher * $rateAmount->default_rate;
               // $totalAmount = max($rateAmount->min_rate ?? 0, $calculatedAmount);

                $rateAssign = new RateAssign();
                $rateAssign->teacher_id = $teacherId;
                $rateAssign->rate_head_id = $rateHead->id;
                $rateAssign->session_id = $session_info->id;
                $rateAssign->no_of_items = $studentsPerTeacher;
                $rateAssign->total_amount = $calculatedAmount;
                $rateAssign->save();
            }

            DB::commit();

            Log::info('✅ Verified Computerized Result Assignments saved.', [
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            return response()->json([
                'message' => 'Verified Computerized Result saved successfully.',
                'teacher_ids' => $teacherIds,
                'total_students' => $totalStudents
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error saving Verified Computerized Result: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while saving data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function storeSupervisionUnderChairmanExamCommittee(Request $request)
    {
        try {
            DB::beginTransaction();

            $sessionId = $request->sid;
            $per_stencil_cutting_rate = $request->per_stencil_rate;
            $print_per_stencil_rate = $request->print_per_stencil_rate;
            $per_question_rate = $request->per_question_rate;

            $session = LocalData::getOrCreateSession($sessionId);

            /**
             * ========== 1. Stencil Cutting ==========
             */
            $teachersStencilCutting = $request->input('teachersStencilCutting', []);
            $totalStencilsCutting =    $request->input('total_stencils_cutting');

            if (!empty($teachersStencilCutting) && $totalStencilsCutting > 0) {
                $rateHeadCutting = RateHead::where('order_no', '12.a')->first();

                if (!$rateHeadCutting) {
                    $rateHeadCutting = new RateHead();
                    $rateHeadCutting->head='Question';
                    $rateHeadCutting->sub_head='Stencil Cutting';
                    $rateHeadCutting->order_no = '12.a';
                    $rateHeadCutting->exam_type = 1;
                    $rateHeadCutting->dist_type = 'Share';
                    $rateHeadCutting->is_course = 0;
                    $rateHeadCutting->is_student_count = 0;
                    $rateHeadCutting->status = 1;
                    $rateHeadCutting->save();
                    Log::info('RateHead Created: Stencil Cutting', ['head' => $rateHeadCutting]);
                }

                $rateAmountCutting = RateAmount::firstOrNew([
                    'rate_head_id' => $rateHeadCutting->id,
                    'session_id' => $session->id,
                ]);
                if (!$rateAmountCutting->exists) {
                    $rateAmountCutting->default_rate = $per_stencil_cutting_rate;
                    $rateAmountCutting->save();
                    Log::info('RateAmount Created: Stencil Cutting', ['amount' => $rateAmountCutting]);
                }

                $perTeacherCutting = $totalStencilsCutting / count($teachersStencilCutting);
                foreach ($teachersStencilCutting as $teacherId) {
                   // $amount = max($rateAmountCutting->min_rate, $perTeacherCutting * $rateAmountCutting->default_rate);
                    $amount=$perTeacherCutting*$rateAmountCutting->default_rate;

                    RateAssign::create([
                        'teacher_id' => $teacherId,
                        'rate_head_id' => $rateHeadCutting->id,
                        'session_id' => $session->id,
                        'no_of_items' => $perTeacherCutting,
                        'total_amount' => $amount,
                    ]);
                    Log::info('RateAssign Created: Stencil Cutting', ['teacher_id' => $teacherId, 'amount' => $amount]);
                }
            }

            /**
             * ========== 2. Printing ==========
             */
            $teachersPrinting = $request->input('teachersPrinting', []);
            $totalStencilsPrinting =$request->input('total_stencils_printing');

            if (!empty($teachersPrinting) && $totalStencilsPrinting > 0) {
                $rateHeadPrinting = RateHead::where('order_no', '12.b')->first();

                if (!$rateHeadPrinting) {
                    $rateHeadPrinting = new RateHead();
                    $rateHeadPrinting->head='Question';
                    $rateHeadPrinting->sub_head='Printing';
                    $rateHeadPrinting->order_no = '12.b';
                    $rateHeadPrinting->exam_type = 1;
                    $rateHeadPrinting->dist_type = 'Share';
                    $rateHeadPrinting->is_course = 0;
                    $rateHeadPrinting->is_student_count = 0;
                    $rateHeadPrinting->status = 1;
                    $rateHeadPrinting->save();
                    Log::info('RateHead Created: Stencil Cutting', ['head' => $rateHeadPrinting]);
                }

                $rateAmountPrinting = RateAmount::firstOrNew([
                    'rate_head_id' => $rateHeadPrinting->id,
                    'session_id' => $session->id,
                ]);
                if (!$rateAmountPrinting->exists) {
                    $rateAmountPrinting->default_rate = $print_per_stencil_rate;
                    $rateAmountPrinting->save();
                    Log::info('RateAmount Created: Stencil Cutting', ['amount' => $rateAmountPrinting]);
                }

                $perTeacherPrinting = $totalStencilsPrinting / count($teachersPrinting);
                foreach ($teachersPrinting as $teacherId) {
                   // $amount = max($rateAmountPrinting->min_rate, $perTeacherPrinting * $rateAmountPrinting->default_rate);
                    $amount=$perTeacherPrinting*$rateAmountPrinting->default_rate;

                    RateAssign::create([
                        'teacher_id' => $teacherId,
                        'rate_head_id' => $rateHeadPrinting->id,
                        'session_id' => $session->id,
                        'no_of_items' => $perTeacherPrinting,
                        'total_amount' => $amount,
                    ]);
                    Log::info('RateAssign Created: Stencil Cutting', ['teacher_id' => $teacherId, 'amount' => $amount]);
                }
            }

            /**
             * ========== 3. Comparison ==========
             */
            $teachersComparision = $request->input('teachersComparision', []);
            $totalQuestionComparison = $request->input('total_question_comparison');

            if (!empty($teachersComparision) && $totalQuestionComparison > 0) {
                $rateHeadComparison = RateHead::where('order_no', '11')->first();

                if (!$rateHeadComparison) {
                    $rateHeadComparison = new RateHead();
                    $rateHeadComparison->head='Question Typing,Sketching & Misc.';
                    $rateHeadComparison->order_no = '11';
                    $rateHeadComparison->exam_type = 1;
                    $rateHeadComparison->dist_type = 'Share';
                    $rateHeadComparison->is_course = 0;
                    $rateHeadComparison->is_student_count = 0;
                    $rateHeadComparison->status = 1;
                    $rateHeadComparison->save();
                    Log::info('RateHead Created: Stencil Cutting', ['head' => $rateHeadComparison]);
                }

                $rateAmountComparison = RateAmount::firstOrNew([
                    'rate_head_id' => $rateHeadComparison->id,
                    'session_id' => $session->id,
                ]);
                if (!$rateAmountComparison->exists) {
                    $rateAmountComparison->default_rate = $per_question_rate;
                    $rateAmountComparison->save();
                    Log::info('RateAmount Created: Stencil Cutting', ['amount' => $rateAmountComparison]);
                }

                $perTeacherComparison = $totalQuestionComparison / count($teachersComparision);
                foreach ($teachersComparision as $teacherId) {
                    $amount=$perTeacherComparison*$rateAmountComparison->default_rate;
                    RateAssign::create([
                        'teacher_id' => $teacherId,
                        'rate_head_id' => $rateHeadComparison->id,
                        'session_id' => $session->id,
                        'no_of_items' => $perTeacherComparison,
                        'total_amount' => $amount,
                    ]);
                    Log::info('RateAssign Created: Stencil Cutting', ['teacher_id' => $teacherId, 'amount' => $amount]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Committee supervision data stored successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to store data',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function storeAdvisorStudent(Request $request)
    {
        $teacherIds = $request->input('advisorTeacherIds', []);
        $studentCounts = $request->input('advisorTotal_students', []);
        $sessionId = $request->sid;
        $advisor_per_student_rate = $request->advisor_per_student_rate;

        Log::info('📥 Submitted Teacher IDs: ', $teacherIds);
        Log::info('📥 Submitted Student Counts: ', $studentCounts);

        $errors = [];

        // ✅ Validation
        if (empty($teacherIds) || empty($studentCounts)) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => [
                    'advisorTeacherIds' => 'Teacher IDs are required.',
                    'advisorTotal_students' => 'Student counts are required.',
                ]
            ], 422);
        }

        try {
            DB::beginTransaction();

            // ✅ Step 1: Create or fetch RateHead
            $rateHead = RateHead::where('order_no', '13')
                ->first();

            if (!$rateHead) {
                $rateHead = new RateHead();
                $rateHead->order_no = 13;
                $rateHead->head = 'Advisor Fee';
                $rateHead->exam_type = 1;
                $rateHead->dist_type = 'Individual';
                $rateHead->is_course = 0;
                $rateHead->is_student_count = 1;
                $rateHead->marge_with = null;
                $rateHead->status = 1;
                $rateHead->save();
                Log::info('✅ RateHead Created or Fetched (Advisor-Student)', $rateHead->toArray());
            }


            // ✅ Step 2: Create or fetch Session
            $session = LocalData::getOrCreateSession($sessionId);

            // ✅ Step 3: Create or fetch RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = $advisor_per_student_rate; // Example rate per student
                $rateAmount->save();

                Log::info('✅ RateAmount Created (Advisor-Student)', $rateAmount->toArray());
            }

            // ✅ Step 4: Assign teachers
            foreach ($teacherIds as $index => $teacherId) {
                $studentCount = (int) ($studentCounts[$index] ?? 0);

                if ($studentCount > 0) {
                    //$amount = max($rateAmount->min_rate, $studentCount * $rateAmount->default_rate);
                    $amount=$studentCount * $rateAmount->default_rate;

                    $rateAssign = RateAssign::create([
                        'teacher_id' => $teacherId,
                        'rate_head_id' => $rateHead->id,
                        'session_id' => $session->id,
                        'no_of_items' => $studentCount,
                        'total_amount' => $amount,
                    ]);

                    Log::info("✅ RateAssign Created for Teacher ID $teacherId", $rateAssign->toArray());
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Advisor-Student assignments saved successfully!',
                'teacher_ids' => $teacherIds,
                'student_counts' => $studentCounts
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error saving Advisor-Student data: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while saving Advisor-Student data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /*$teacherIds
    [
    161,
    120
    ]
    $amounts
    [
    161 => 12,
    120 => 9
    ]*/
    public function storeVerifiedFinalGraduationResult(Request $request)
    {
        $teacherIds = $request->input('verified_grade_teacher_ids', []);
        $amounts = $request->input('verified_grade_amounts', []);
        $sessionId = $request->sid;
        $final_result_per_student_rate=$request->final_result_per_student_rate;


        Log::info('📝 Teacher IDs:', $teacherIds);
        Log::info('🧮 Student Amounts:', $amounts);

        if (empty($teacherIds) || empty($amounts)) {
            return response()->json([
                'message' => 'Teacher IDs and amounts are required.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Step 1: Get or create RateHead
            $rateHead = RateHead::where('order_no', '16')->first();

            if (!$rateHead) {
                $rateHead = RateHead::create([
                    'order_no' => 16,
                    'head' => 'Verified of Final Graduation Result',
                    'exam_type' => 1,
                    'dist_type' => 'Individual',
                    'enable_min' => 0,
                    'enable_max' => 0,
                    'is_course' => 0,
                    'is_student_count' => 1,
                    'marge_with' => null,
                    'status' => 1,
                ]);
                Log::info('✅ RateHead Created', $rateHead->toArray());
            }

            // Step 2: Get or create Session
            $session = LocalData::getOrCreateSession($sessionId);

            // Step 3: Get or create RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = $final_result_per_student_rate; // Set your rate per student
                $rateAmount->save();

                Log::info('✅ RateAmount Created', $rateAmount->toArray());
            }

            // Step 4: Assign each teacher
            foreach ($teacherIds as $index => $teacherId) {
                $studentCount = (int) ($amounts[$index] ?? 0);

                if ($studentCount > 0) {
                    //$totalAmount = max($rateAmount->min_rate, $studentCount * $rateAmount->default_rate);
                    $totalAmount=$studentCount * $rateAmount->default_rate;

                    $rateAssign = RateAssign::create([
                        'teacher_id' => $teacherId,
                        'rate_head_id' => $rateHead->id,
                        'session_id' => $session->id,
                        'no_of_items' => $studentCount,
                        'total_amount' => $totalAmount,
                    ]);

                    Log::info("✅ RateAssign created for Teacher ID $teacherId", $rateAssign->toArray());
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Verified Final Graduation Result data saved successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error saving Verified Final Graduation Result: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function storeConductedCentralOralExam(Request $request)
    {
        $teacherIds = $request->input('conducted_oral_exam_teacher_ids', []);
        $amounts = $request->input('conducted_oral_exam_amounts', []);
        $sessionId = $request->sid;
        $central_examination_thesis_rate = $request->central_examination_thesis_rate;

        Log::info('📝 Teacher IDs:', $teacherIds);
        Log::info('🧮 Student Amounts:', $amounts);

        if (empty($teacherIds) || empty($amounts)) {
            return response()->json([
                'message' => 'Teacher IDs and amounts are required.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Step 1: Get or create RateHead
            $rateHead = RateHead::where('order_no', '7.e')->first();

            if (!$rateHead) {
                $rateHead = RateHead::create([
                    'order_no' => '7.e',
                    'head' => 'Sessional',
                    'sub_head' => 'Central Viva',
                    'exam_type' => 1,
                    'dist_type' => 'Individual',
                    'enable_min' => 0,
                    'enable_max' => 0,
                    'is_course' => 0,
                    'is_student_count' => 1,
                    'marge_with' => null,
                    'status' => 1,
                ]);
                Log::info('✅ RateHead Created', $rateHead->toArray());
            }

            // Step 2: Get or create Session
            $session = LocalData::getOrCreateSession($sessionId);

            // Step 3: Get or create RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = $central_examination_thesis_rate; // Set your rate per student
                $rateAmount->save();

                Log::info('✅ RateAmount Created', $rateAmount->toArray());
            }

            // Step 4: Assign each teacher
            foreach ($teacherIds as $index => $teacherId) {
                $studentCount = (int) ($amounts[$index] ?? 0);

                if ($studentCount > 0) {
                    //$totalAmount = max($rateAmount->min_rate, $studentCount * $rateAmount->default_rate);
                    $totalAmount=$studentCount * $rateAmount->default_rate;

                    $rateAssign = RateAssign::create([
                        'teacher_id' => $teacherId,
                        'rate_head_id' => $rateHead->id,
                        'session_id' => $session->id,
                        'no_of_items' => $studentCount,
                        'total_amount' => $totalAmount,
                    ]);

                    Log::info("✅ RateAssign created for Teacher ID $teacherId", $rateAssign->toArray());
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Verified Cetral Oral Examination saved successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error saving Verified Final Graduation Result: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storeInvolvedSurvey(Request $request)
    {
        $teacherIds = $request->input('involved_survey_teacher_ids'); // array
        $amounts = $request->input('involved_survey_student_amounts');        // array (indexed)
        $sessionId = $request->sid;
        $servey_rate = $request->servey_rate;

        Log::info('📝 Teacher IDs:', $teacherIds);
        Log::info('🧮 Student Amounts:', $amounts);

        if (empty($teacherIds) || empty($amounts)) {
            return response()->json([
                'message' => 'Teacher IDs and amounts are required.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Step 1: Get or create RateHead
            $rateHead = RateHead::where('order_no', '7.f')->first();

            if (!$rateHead) {
                $rateHead = RateHead::create([
                    'order_no' => '7.f',
                    'head' => 'Sessional',
                    'sub_head' => 'Survey',
                    'exam_type' => 1,
                    'dist_type' => 'Individual',
                    'enable_min' => 0,
                    'enable_max' => 0,
                    'is_course' => 0,
                    'is_student_count' => 1,
                    'marge_with' => null,
                    'status' => 1,
                ]);
                Log::info('✅ RateHead Created', $rateHead->toArray());
            }

            // Step 2: Get or create Session
            $session = LocalData::getOrCreateSession($sessionId);

            // Step 3: Get or create RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = $servey_rate; // Set your rate per student
                $rateAmount->save();

                Log::info('✅ RateAmount Created', $rateAmount->toArray());
            }

            // Step 4: Assign each teacher
            foreach ($teacherIds as $index => $teacherId) {
                $studentCount = (int) ($amounts[$index] ?? 0);

                if ($studentCount > 0) {
                    //$totalAmount = max($rateAmount->min_rate, $studentCount * $rateAmount->default_rate);
                    $totalAmount=$studentCount * $rateAmount->default_rate;

                    $rateAssign = RateAssign::create([
                        'teacher_id' => $teacherId,
                        'rate_head_id' => $rateHead->id,
                        'session_id' => $session->id,
                        'no_of_items' => $studentCount,
                        'total_amount' => $totalAmount,
                    ]);

                    Log::info("✅ RateAssign created for Teacher ID $teacherId", $rateAssign->toArray());
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Involved Survey saved successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error saving Verified Final Graduation Result: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storeConductedPreliminaryViva(Request $request)
    {
        $teacherIds = $request->input('conducted_preliminary_viva_teacher_ids'); // array
        $amounts = $request->input('conducted_preliminary_viva_student_amounts');        // array (indexed)
        $sessionId = $request->sid;
        $viva_thesis_project_rate = $request->viva_thesis_project_rate;

        // Step 1: Get or create Session
        $session_info = LocalData::getOrCreateSession($sessionId);

        Log::info('📝 Teacher IDs:', $teacherIds);
        Log::info('🧮 Student Amounts:', $amounts);
        Log::info('📘 Session Info Retrieved or Created:', $session_info->toArray());

        if (empty($teacherIds) || empty($amounts)) {
            return response()->json([
                'message' => 'Teacher IDs and amounts are required.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Step 2: Get or create RateHead
            $rateHead = RateHead::where('order_no', '6.c')->first();

            if (!$rateHead) {
                $rateHead = RateHead::create([
                    'order_no' => '6.c',
                    'head' => 'Project/Thesis',
                    'sub_head' =>  'Initial Viva ' . ($session_info->year . '/' . $session_info->semester),
                    'exam_type' => 1,
                    'dist_type' => 'Individual',
                    'enable_min' => 0,
                    'enable_max' => 0,
                    'is_course' => 0,
                    'is_student_count' => 1,
                    'marge_with' => null,
                    'status' => 1,
                ]);
                Log::info('✅ RateHead Created', $rateHead->toArray());
            }



            // Step 3: Get or create RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = $viva_thesis_project_rate; // Set your rate per student
                $rateAmount->save();

                Log::info('✅ RateAmount Created', $rateAmount->toArray());
            }

            // Step 4: Assign each teacher
            foreach ($teacherIds as $index => $teacherId) {
                $studentCount = (int) ($amounts[$index] ?? 0);

                if ($studentCount > 0) {
                    //$totalAmount = max($rateAmount->min_rate, $studentCount * $rateAmount->default_rate);
                    $totalAmount=$studentCount * $rateAmount->default_rate;

                    $rateAssign = RateAssign::create([
                        'teacher_id' => $teacherId,
                        'rate_head_id' => $rateHead->id,
                        'session_id' => $session_info->id,
                        'no_of_items' => $studentCount,
                        'total_amount' => $totalAmount,
                    ]);

                    Log::info("✅ RateAssign created for Teacher ID $teacherId", $rateAssign->toArray());
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Preliminary Viva saved successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error saving Verified Final Graduation Result: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storeExaminedThesisProject(Request $request)
    {
        $teacherIds = $request->input('examined_thesis_project_teacher_ids', []);
        $internalAmounts = $request->input('examined_internal_thesis_project_student_amounts', []);
        $externalAmounts = $request->input('examined_external_thesis_project_student_amounts', []);
        $sessionId = $request->sid;
        $examined_thesis_project_rate = $request->examined_thesis_project_rate;



        Log::info('🎓 Teacher IDs:', $teacherIds);
        Log::info('📘 Internal Amounts:', $internalAmounts);
        Log::info('📗 External Amounts:', $externalAmounts);

        if (empty($teacherIds)) {
            return response()->json(['message' => 'No teacher data submitted.'], 422);
        }

        try {
            // Step 1: Get or create Session
            $session_info = LocalData::getOrCreateSession($sessionId);
            Log::info('✅ Session Info Created', $session_info->toArray());


            DB::beginTransaction();

            // 2. Get or Create RateHead
            $rateHead = RateHead::where('order_no', '6.a')->first();
            if (!$rateHead) {
                $rateHead = RateHead::create([
                    'order_no' => '6.a',
                    'head' => 'Project/Thesis',
                    'sub_head' => 'Examination',
                    'exam_type' => 1,
                    'dist_type' => 'Individual',
                    'enable_min' => 0,
                    'enable_max' => 0,
                    'is_course' => 0,
                    'is_student_count' => 1,
                    'marge_with' => null,
                    'status' => 1,
                ]);
                Log::info('✅ RateHead Created', $rateHead->toArray());
            }


            // 3. Get or Create RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = $examined_thesis_project_rate; // adjust if needed
                $rateAmount->save();
                Log::info('✅ RateAmount Created', $rateAmount->toArray());
            }

            // 4. Create RateAssign for each teacher
            foreach ($teacherIds as $index => $teacherId) {
                $internal = (int) ($internalAmounts[$index] ?? 0);
                $external = (int) ($externalAmounts[$index] ?? 0);
                $totalStudents = $internal + $external;

                if ($totalStudents > 0) {
                   // $totalAmount = max($rateAmount->min_rate, $totalStudents * $rateAmount->default_rate);
                    $totalAmount=$totalStudents*$rateAmount->default_rate;

                    $rateAssign = RateAssign::create([
                        'teacher_id' => $teacherId,
                        'rate_head_id' => $rateHead->id,
                        'session_id' => $session_info->id,
                        'no_of_items' => $totalStudents,
                        'total_amount' => $totalAmount,
                    ]);

                    Log::info("✅ RateAssign created for Teacher ID {$teacherId}", $rateAssign->toArray());
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Examined Thesis/Project data saved successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error storing Examined Thesis/Project: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while saving data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function storeConductedOralExamination(Request $request)
    {
        $teacherIds = $request->input('conducted_oral_examination_teacher_ids'); // array
        $amounts = $request->input('conducted_oral_examination_student_amounts');        // array (indexed)
        $sessionId = $request->sid;
        $oral_exam_thesis_project = $request->oral_exam_thesis_project;


        Log::info('📝 Teacher IDs:', $teacherIds);
        Log::info('🧮 Student Amounts:', $amounts);

        if (empty($teacherIds) || empty($amounts)) {
            return response()->json([
                'message' => 'Teacher IDs and amounts are required.'
            ], 422);
        }

        try {
            // Step 1: Get or create Session
            $session_info = LocalData::getOrCreateSession($sessionId);
            Log::info('sessino info',$session_info->toArray());

            DB::beginTransaction();

            // Step 2: Get or create RateHead
            $rateHead = RateHead::where('order_no', '6.d')->first();

            if (!$rateHead) {
                $rateHead = RateHead::create([
                    'order_no' => '6.d',
                    'head' => 'Project/Thesis',
                    'sub_head' => 'Final Viva ' . $session_info->year . '/' . $session_info->semester,
                    'exam_type' => 1,
                    'dist_type' => 'Individual',
                    'enable_min' => 0,
                    'enable_max' => 0,
                    'is_course' => 0,
                    'is_student_count' => 1,
                    'marge_with' => null,
                    'status' => 1,
                ]);
                Log::info('✅ RateHead Created', $rateHead->toArray());
            }



            // Step 3: Get or create RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = $oral_exam_thesis_project; // Set your rate per student
                $rateAmount->save();

                Log::info('✅ RateAmount Created', $rateAmount->toArray());
            }

            // Step 4: Assign each teacher
            foreach ($teacherIds as $index => $teacherId) {
                $studentCount = (int) ($amounts[$index] ?? 0);

                if ($studentCount > 0) {
                    //$totalAmount = max($rateAmount->min_rate, $studentCount * $rateAmount->default_rate);
                    $totalAmount=$studentCount * $rateAmount->default_rate;

                    $rateAssign = RateAssign::create([
                        'teacher_id' => $teacherId,
                        'rate_head_id' => $rateHead->id,
                        'session_id' => $session_info->id,
                        'no_of_items' => $studentCount,
                        'total_amount' => $totalAmount,
                    ]);

                    Log::info("✅ RateAssign created for Teacher ID $teacherId", $rateAssign->toArray());
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Conducted Oral Exam saved successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error saving Verified Final Graduation Result: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storeSupervisedThesisProject(Request $request)
    {
        $teacherIds = $request->input('supervised_thesis_project_teacher_ids'); // array
        $amounts = $request->input('supervised_thesis_project_student_amounts');        // array (indexed)
        $sessionId = $request->sid;
        $supervised_thesis_project_rate = $request->supervised_thesis_project_rate;


        Log::info('📝 Teacher IDs:', $teacherIds);
        Log::info('🧮 Student Amounts:', $amounts);

        if (empty($teacherIds) || empty($amounts)) {
            return response()->json([
                'message' => 'Teacher IDs and amounts are required.'
            ], 422);
        }

        try {
            // Step 1: Get or create Session
            $session_info = LocalData::getOrCreateSession($sessionId);
            Log::info('sessino info',$session_info->toArray());

            DB::beginTransaction();

            // Step 2: Get or create RateHead
            $rateHead = RateHead::where('order_no', '6.b')->first();

            if (!$rateHead) {
                $rateHead = RateHead::create([
                    'order_no' => '6.b',
                    'head' => 'Project/Thesis',
                    'sub_head' => 'Supervising',
                    'exam_type' => 1,
                    'dist_type' => 'Individual',
                    'enable_min' => 0,
                    'enable_max' => 0,
                    'is_course' => 0,
                    'is_student_count' => 1,
                    'marge_with' => null,
                    'status' => 1,
                ]);
                Log::info('✅ RateHead Created', $rateHead->toArray());
            }



            // Step 3: Get or create RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = $supervised_thesis_project_rate; // Set your rate per student
                $rateAmount->save();

                Log::info('✅ RateAmount Created', $rateAmount->toArray());
            }

            // Step 4: Assign each teacher
            foreach ($teacherIds as $index => $teacherId) {
                $studentCount = (int) ($amounts[$index] ?? 0);

                if ($studentCount > 0) {
                    //$totalAmount = max($rateAmount->min_rate, $studentCount * $rateAmount->default_rate);
                    $totalAmount=$studentCount * $rateAmount->default_rate;

                    $rateAssign = RateAssign::create([
                        'teacher_id' => $teacherId,
                        'rate_head_id' => $rateHead->id,
                        'session_id' => $session_info->id,
                        'no_of_items' => $studentCount,
                        'total_amount' => $totalAmount,
                    ]);

                    Log::info("✅ RateAssign created for Teacher ID $teacherId", $rateAssign->toArray());
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Supervised Thesis/Project saved successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error saving Supervised Thesis/Project: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function storeHonorariumCoordinatorCommittee(Request $request)
    {
        // If validation passes, extract values
        $teacherId = $request->input('coordinator_id');
        $amount = $request->input('coordinator_amount');
        $sessionId=$request->input('sid');

        Log::info('👨‍🏫 Chairman Teacher ID:', [$teacherId]);
        Log::info('💰 Chairman Amount:', [$amount]);

        try {
            // Step 1: Get or create session
            $session_info = LocalData::getOrCreateSession($sessionId);
            Log::info('📘 Session Info:', $session_info->toArray());

            DB::beginTransaction();

            // Step 2: Get or create RateHead
            $rateHead = RateHead::where('order_no', '14')->first();

            if (!$rateHead) {
                $rateHead = RateHead::create([
                    'order_no' => '14',
                    'head' => 'Course Co-ordinator Fee',
                    'exam_type' => 1,
                    'dist_type' => 'Individual',
                    'enable_min' => 0,
                    'enable_max' => 0,
                    'is_course' => 0,
                    'is_student_count' => 0,
                    'marge_with' => null,
                    'status' => 1,
                ]);
                Log::info('✅ RateHead Created:', $rateHead->toArray());
            }

            // Step 3: Get or create RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = 3600; // Set your rate per student
                $rateAmount->save();
                Log::info('✅ RateAmount Created', $rateAmount->toArray());
            }


            // Step 4: Create RateAssign
            $rateAssign = RateAssign::create([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
                'teacher_id' => $teacherId,
                'rate_amount_id' => $rateAmount->id,
                'total_amount' => $amount,
            ]);
            Log::info('📝 RateAssign Created:', $rateAssign->toArray());

            DB::commit();

            return response()->json(['message' => 'Course Co-ordinator Honorarium saved successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error Storing Chairman Honorarium:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    public function storeHonorariumChairmanCommittee(Request $request)
    {
        $teacherId = $request->input('chairman_id');
        $amount = $request->input('chairman_amount');
        $sessionId=$request->input('sid');

        Log::info('👨‍🏫 Chairman Teacher ID:', [$teacherId]);
        Log::info('💰 Chairman Amount:', [$amount]);

        try {
            // Step 1: Get or create session
            $session_info = LocalData::getOrCreateSession($sessionId);
            Log::info('📘 Session Info:', $session_info->toArray());

            DB::beginTransaction();

            // Step 2: Get or create RateHead
            $rateHead = RateHead::where('order_no', '15')->first();

            if (!$rateHead) {
                $rateHead = RateHead::create([
                    'order_no' => '15',
                    'head' => 'Chairman Fee',
                    'exam_type' => 1,
                    'dist_type' => 'Individual',
                    'enable_min' => 0,
                    'enable_max' => 0,
                    'is_course' => 0,
                    'is_student_count' => 0,
                    'marge_with' => null,
                    'status' => 1,
                ]);
                Log::info('✅ RateHead Created:', $rateHead->toArray());
            }

            // Step 3: Get or create RateAmount
            $rateAmount = RateAmount::firstOrNew([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
            ]);

            if (!$rateAmount->exists) {
                $rateAmount->default_rate = 4500; // Set your rate per student
                $rateAmount->save();
                Log::info('✅ RateAmount Created', $rateAmount->toArray());
            }


            // Step 4: Create RateAssign
            $rateAssign = RateAssign::create([
                'rate_head_id' => $rateHead->id,
                'session_id' => $session_info->id,
                'teacher_id' => $teacherId,
                'rate_amount_id' => $rateAmount->id,
                'total_amount' => $amount,
            ]);
            Log::info('📝 RateAssign Created:', $rateAssign->toArray());

            DB::commit();

            return response()->json(['message' => 'Chairman Honorarium saved successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error Storing Chairman Honorarium:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }


    public function session_wise_sessional_courses(Request $request, $sid)
    {
        $result = ApiData::getSessionWiseSessionalCourses($request, $sid);

        return response()->json($result);
    }


}
