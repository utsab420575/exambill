<?php

use App\Http\Controllers\PdfController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});



//Auth::routes();
Route::get('/dashboard-bill',[HomeController::class,'dashboard'])->name('dashboard');

//All Session Show
Route::get('/sessions/regular', [SessionController::class, 'allRegularSessoins'])->name('sessions.regular');
Route::get('/sessions/review', [SessionController::class, 'allReviewSessions'])->name('sessions.review');

//Homepage Work
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/check', [HomeController::class, 'check'])->name('check')->middleware(['roles:user']);

//Regular and Review Form
Route::get('/sessions/regular/form/{sid}',[StaffController::class,'sessionsRegularForm'])->name('sessions.regular.form');

//Report Session
Route::get('/report/sessions/regular', [PdfController::class, 'allReportRegularSessions'])->name('report.sessions.regular');
Route::get('/report/sessions/review', [PdfController::class, 'allReportReviewSessions'])->name('report.sessions.review');
Route::get('/report/sessions/regular/generate/{sid}', [PdfController::class, 'reportSessionsRegularGenerate'])->name('report.sessions.regular.generate');

Route::get('/regular-previous-sessions',[StaffController::class,'regular_previous_sessions']);
Route::get('/review-previous-sessions',[StaffController::class,'review_previous_sessions']);
Route::get('/session-wise-theory-courses/{sid}',[StaffController::class,'session_wise_theory_courses']);
Route::post('/examination/moderation/committee/store', [StaffController::class, 'storeExaminationModerationCommittee'])->name('examination.moderation.committee.store');
Route::post('/examiner/paper/setter/store', [StaffController::class, 'storeExaminerPaperSetter'])->name('examiner.paper.setter.store');
Route::post('/class/test/teacher/store', [StaffController::class, 'storeClassTestTeacherStore'])->name('class.test.teacher.store');
Route::get('/session-wise-sessional-courses/{sid}',[StaffController::class,'session_wise_sessional_courses']);
Route::post('/sessional/course/teacher/store', [StaffController::class, 'storeSessionalCourseTeacher'])->name('sessional.course.teacher.store');
Route::post('/scrutinizers/store', [StaffController::class, 'storeScrutinizers'])->name('scrutinizers.store');
Route::post('/theory/grade/sheet/store', [StaffController::class, 'storeTheoryGradeSheet'])->name('theory.grade.sheet.store');
Route::post('/sessional/grade/sheet/store', [StaffController::class, 'storeSessionalGradeSheet'])->name('sessional.grade.sheet.store');
Route::post('/scrutinizers/theory/grade/sheet/store', [StaffController::class, 'storeScrutinizersTheoryGradeSheet'])->name('scrutinizers.theory.grade.sheet.store');
Route::post('/scrutinizers/sessional/grade/sheet/store', [StaffController::class, 'storeScrutinizersSessionalGradeSheet'])->name('scrutinizers.sessional.grade.sheet.store');
Route::post('/prepare/computerized/result/store', [StaffController::class, 'storePreparedComputerizedResult'])->name('prepare.computerized.result.store');
Route::post('/verified/computerized/result/store', [StaffController::class, 'storeVerifiedComputerizedResult'])->name('verified.computerized.result.store');
Route::post('/supervision/under/chairman/exam/committee/store', [StaffController::class, 'storeSupervisionUnderChairmanExamCommittee'])->name('supervision.under.chairman.exam.committee.store');
Route::post('/advisor/student/store', [StaffController::class, 'storeAdvisorStudent'])->name('advisor.student.store');
Route::post('/verified/final/graduation/result/store', [StaffController::class, 'storeVerifiedFinalGraduationResult'])->name('verified.final.graduation.result.store');
Route::post('/conducted/central/oral/exam/store', [StaffController::class, 'storeConductedCentralOralExam'])->name('conducted.central.oral.exam.store');
Route::post('/involved/survey/store', [StaffController::class, 'storeInvolvedSurvey'])->name('involved.survey.store');
Route::post('/conducted/preliminary/viva/store', [StaffController::class, 'storeConductedPreliminaryViva'])->name('conducted.preliminary.viva.store');
Route::post('/conducted/oral/examination/store', [StaffController::class, 'storeConductedOralExamination'])->name('conducted.oral.examination.store');
Route::post('/supervised/thesis/project/store', [StaffController::class, 'storeSupervisedThesisProject'])->name('supervised.thesis.project.store');
Route::post('/examined/thesis/project/store', [StaffController::class, 'storeExaminedThesisProject'])->name('examined.thesis.project.store');
Route::post('/honorarium/coordinator/committee/store', [StaffController::class, 'storeHonorariumCoordinatorCommittee'])->name('honorarium.coordinator.committee.store');
Route::post('/honorarium/chairman/committee/store', [StaffController::class, 'storeHonorariumChairmanCommittee'])->name('honorarium.chairman.committee.store');





