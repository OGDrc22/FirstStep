<?php

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ExamResultController;
use App\Http\Controllers\StartExamController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\RetrieveResultController;
use App\Models\ExamJob;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PreventDirectAccess;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\QRController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::post('/send-result-email', [RetrieveResultController::class, 'sendResultEmail'])
    ->name('send.result.email');

Route::post('/send-result-email-exam', [ExamResultController::class, 'sendResultEmail'])
    ->name('send.result.email.from.exam');


Route::post('/generate-qr-link', [QRController::class, 'generateQRLink']);

Route::get('/download-result/{file}', function ($file) {
    $path = storage_path('app/public/results/' . $file);
    
    if (file_exists($path)) {
        // This 'download' function tells the phone: "Don't just show this, SAVE it."
        return response()->download($path, 'First-Step.png');
    }
    
    return abort(404);
})->name('download-result');

Route::get('/view-result', [QRController::class, 'viewResult'])->name('view.result');


// Route::get('/preview-email', function () {

//     return view('emails.all_result', [
//         "username" => "dev",
//         "useremail" => "dennies.chavez.coi@pcu.edu.ph",
//         "recommendedTrack" => "Computer Science",
//         "note" => "Based on your overall performance, with a top score of 37.95%, the Computer Science pathway is highly recommended. This is primarily due to your strong aptitude for abstract reasoning and problem-solving, which are essential for software development and data-driven fields.",
//         "secondaryTrack" => "Information Technology",
//         "examResult" => collect([
//             (object)[
//                 'created_at' => now(),
//                 'predicted_track' => ['track' => 'Computer Science'],
//                 'secondary_track' => ['track' => 'IT']
//             ]
//         ]),
//         'results' => [
//             "action" => "all",
//             "averageAcc" => "",
//             "averageDuration" =>  [
//                 "Computer Science" => 8.4,
//                 "Preference" => 1.6,
//                 "Information Technology" => 0.0,
//                 "Computer Engineering" => 0.0,
//                 "Multimedia Arts" => 0.0,
//             ],
//             "computedTrackPercentage" => [
//                 "Information Technology" => 18.0,
//                 "Computer Engineering" => 18.0,
//                 "Computer Science" => 37.954200283688,
//                 "Multimedia Arts" => 18.0,
//             ],
//             "trackPercentage" => [
//                 "Computer Engineering" => [
//                 "track" => "Computer Engineering",
//                 "percentage" => 22.08,
//                 ],
//                 "Computer Science" => [
//                 "track" => "Computer Science",
//                 "percentage" => 42.56,
//                 ],
//                 "Information Technology" => [
//                 "track" => "Information Technology",
//                 "percentage" => 13.27,
//                 ],
//                 "Multimedia Arts" => [
//                 "track" => "Multimedia Arts",
//                 "percentage" => 22.09,
//                 ]
//             ],
//             "dateAttmpt" =>[
//                 0 => "2026-04-18 08:36:32",
//                 1 => "2026-04-18 08:34:09",
//                 2 => "2026-04-18 08:04:10",
//                 3 => "2026-04-18 07:01:29",
//                 4 => "2026-04-18 07:01:03"
//             ]
//         ]
//     ]);
// });

Route::get('/assessment-entry', [AssessmentController::class, 'showAssessmentEntryForm'])->name('assessment-entry');
// Route::post('/login-data', [AssessmentController::class, 'login_data'])->name('login-data');
// Route::post('/logout', [AssessmentController::class, 'logout'])->name('logout');
Route::get('/retrieve-result', [RetrieveResultController::class, 'showForm'])->name('retrieve.result');
Route::post('/get-result', [RetrieveResultController::class, 'processResult']);
Route::get('/get-result', [RetrieveResultController::class, 'getResult'])->name('get.result');
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
    Route::post('/submit-exam', [ExamResultController::class, 'submitExam'])
        ->name('submit.exam');
    // Route::post('/submit-exam', function () {
    //     dd("heellooo");;
    // })->name('submit.exam');
});
