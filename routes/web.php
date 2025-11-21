<?php

use App\Http\Controllers\WelcomeSampleController;
use App\Http\Controllers\ExamReasultController;
use App\Http\Controllers\StartExamController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RetrieveResultController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/start-exam', function () {
//     return view('start_exam');
// });

// Route::get('/', [WelcomeSampleController::class, 'runPython']);
// Route::get('/start-exam', [StartExamController::class, 'runPython']);
Route::post('/start-exam', [StartExamController::class, 'getView']);
Route::get('/start-exam', [StartExamController::class, 'getView'])->name('start-exam');
Route::post('/submit-exam', [ExamReasultController::class, 'submitExam'])->name('submit-exam');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/start-exam', [LoginController::class, 'start']);
Route::post('/login-data', [LoginController::class, 'login_data']);
Route::get('/retrieve-result', [RetrieveResultController::class, 'showForm']);
Route::post('/get-result', [RetrieveResultController::class, 'getResult']);