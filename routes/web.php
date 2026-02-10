<?php

use App\Http\Controllers\WelcomeSampleController;
use App\Http\Controllers\ExamReasultController;
use App\Http\Controllers\StartExamController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\RetrieveResultController;
use App\Models\ExamJob;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PreventDirectAccess;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');



Route::get('/assessment-entry', [AssessmentController::class, 'showAssessmentEntryForm'])->name('asessment-entry');
Route::post('/login-data', [AssessmentController::class, 'login_data'])->name('login-data');
Route::post('/logout', [AssessmentController::class, 'logout'])->name('logout');
Route::get('/retrieve-result', [RetrieveResultController::class, 'showForm']);
Route::post('/get-result', [RetrieveResultController::class, 'getResult']);
// Route::post('/get-all-result', [RetrieveResultController::class, 'getAllResult']);
Route::post('/generate-exam', [AssessmentController::class, 'generateExam'])->name('generate-exam');
// Route::post('/generate-exam', [StartExamController::class, 'getView'])->name('generate-exam');
Route::middleware(['web'])->group(function () {
    // Route::get('/show-exam', [AssessmentController::class, 'showExam'])->name('show-exam');
    // Route::get('/exam-status', [AssessmentController::class, 'examStatus'])->name('exam-status');
    
    Route::get('/show-exam/{job}', [AssessmentController::class, 'showExam'])
    ->name('show-exam')->whereNumber('job');

    Route::get('/exam/status/{job}',  function (ExamJob $job) {
        return response()->json($job);
    });

    Route::get('/show-exam-result/{id}', [RetrieveResultController::class, 'getSpecificExam'])->name('show-exam-result');

    
    // Protected route
    Route::post('/submit-exam', [ExamReasultController::class, 'submitExam'])
        ->name('submit.exam');
    // Route::post('/submit-exam', function () {
    //     dd("heellooo");;
    // })->name('submit.exam');
});
