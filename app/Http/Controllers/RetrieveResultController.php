<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamResult;
use App\Models\student_tb;
use App\Services\RetrieveResultService;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExamResultMail;
use Illuminate\Support\Facades\RateLimiter;
use function PHPUnit\Framework\isNull;

class RetrieveResultController extends Controller
{
    public function showForm()
    {
        return view('retrieve_result');
    }

    public function processResult(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        session(['resultRequest' => $request->all()]);
        $examResult = ExamResult::whereHas('student', function ($query) use ($request) {
            $query->where('email', $request['email']);
        })
            ->orderBy('id', 'desc')
            ->first();
        $email = $request['email'];
        // dd(empty($examResult), $email);
        if (empty($examResult)) {
            return redirect()->route('retrieve.result')->withErrors(['email_err' => 'No result for ' . $email]);
            // dd(empty($examResult), $email);
        }
        return redirect()->route('get.result');
    }

    public function getResult()
    {
        $request = session('resultRequest');
        // dd($request);
        $examResult = ExamResult::whereHas('student', function ($query) use ($request) {
            $query->where('email', $request['email']);
        })
            ->orderBy('id', 'desc')
            ->first();
        $email = $request['email'];
        // dd($examResult);
        if ($examResult) {
            $action = $request['action'];

            if ($action === 'latest') {
                $action = $request['action'];
                $examResult = ExamResult::whereHas('student', function ($query) use ($request) {
                    $query->where('email', $request['email']);
                })
                    ->latest('id')
                    ->first();

                // dd($examResult);

                $questions = $examResult ? $examResult->questions : null;
                // dd($questions);
                // $keyAns = $examResult ? array_map(function($q) {
                //     return $q['correct_answer'];
                // }, $questions ?? []) : null;
                $username = $examResult ? $examResult->student->name : null;

                $useremail = $examResult ? $examResult->student->email : null;

                $questionsData = $examResult ? $examResult->questionsData : null;

                $predictedTrack = $examResult ? $examResult->predicted_track : null;

                $secondaryTrack = $examResult ? $examResult->secondary_track : null;

                $aptitude = $examResult ? number_format($examResult->aptitude, 0) : null;

                $trackPercentage = $examResult ? $examResult->track_percentage : null;

                $coreCompetencies = $examResult ? $examResult->core_competencies : null;

                $detailedCompetencyLevels = $examResult ? $examResult->detailed_competencies : null;

                $acc_per_category = $examResult ? $examResult->accuracy_per_category : null;

                $duration_per_category = $examResult ? $examResult->duration_per_category : null;

                $note = $examResult ? $examResult->evaluation_note : null;

                $model_accuracy = $examResult ? $examResult->model_accuracy : null;

                $resultN = compact(
                    'action',
                    'username',
                    'useremail',
                    'questionsData',
                    'predictedTrack',
                    'secondaryTrack',
                    'aptitude',
                    'trackPercentage',
                    'coreCompetencies',
                    'detailedCompetencyLevels',
                    'acc_per_category',
                    'duration_per_category',
                    'note',
                    'model_accuracy'
                );

                session(['resultN' => $resultN]);

                // dd($questionsData, $predictedTrack, $secondaryTrack, $trackPercentage, $coreCompetencies, $detailedCompetencyLevels, $acc_per_category, $duration_per_category, $note, $model_accuracy);
                $redirect = view('retrieve_result', compact(
                    'action',
                    'username',
                    'useremail',
                    'examResult',
                    'questions',
                    'questionsData',
                    'predictedTrack',
                    'secondaryTrack',
                    'aptitude',
                    'trackPercentage',
                    'model_accuracy',
                    'acc_per_category',
                    'duration_per_category',
                    'coreCompetencies',
                    'detailedCompetencyLevels',
                    'note',
                    'resultN'
                ));

                return $redirect;

            } elseif ($action === 'all') {

                $action = $request['action'];

                $examResult = ExamResult::whereHas('student', function ($query) use ($request) {
                    $query->where('email', $request['email']);
                })
                    ->latest('id')
                    ->get();

                if ($examResult->isEmpty()) {
                    return redirect()->back()
                        ->withErrors(['email_err' => 'No exam results found for ' . $request['email']]);
                }

                $firstAttempt = $examResult->first();
                $username = null;
                if ($firstAttempt && $firstAttempt->student) {
                    $username = $firstAttempt->student->name;
                }
                $useremail = $firstAttempt->student->email;

                $service = new RetrieveResultService();

                $result = $service->analyzeAllAttempts($examResult);
                // dd($result);

                $recommendedTrack = $result['recommended_track'];
                $secondaryTrack = $result['second_recommendation'];
                $averageAcc = $result['averageAcc'];
                $averageDuration = $result['averageDuration'];
                $trackPercentage = $result['rawTrackPercentage'];
                $computedTrackPercentage = $result['computedTrackPercentage'];
                $note = $result['note'];

                $dateAttmpt = [];

                foreach ($examResult as $attempt) {
                    $dateAttmpt[] = $attempt->created_at->format('Y-m-d H:i:s');

                }
                $examResult = ExamResult::whereHas('student', function ($query) use ($request) {
                    $query->where('email', $request['email']);
                })
                    ->latest('id')
                    ->take(4)
                    ->get();
                // $trackPercentageA = [];

                // foreach ($examResult as $attempt => $data) {
                //     $trackPercentageA[$attempt]['percentage'] = $attempt->track_percentage;
                // }
                // dd($computedTrackPercentage, $trackPercentage);

                $resultN = compact(
                    'action',
                    'username',
                    'useremail',
                    'recommendedTrack',
                    'note',
                    'secondaryTrack',
                    'averageAcc',
                    'averageDuration',
                    'examResult',
                    'computedTrackPercentage',
                    'trackPercentage',
                    'dateAttmpt'
                );

                session(['resultN' => $resultN]);

                return view('retrieve_result', $resultN);

            } else {
                return redirect()->route('get.result')->withErrors(['action' => 'Invalid action specified.']);
            }
        } else {
            return redirect()->route('retrieve.result')->withErrors(['email_err' => 'No result for ' . $email]);
        }
    }

    public function getSpecificExam($id)
    {
        $examResult = ExamResult::with('student')->findOrFail($id);

        $username = $examResult ? $examResult->student->name : null;

        $useremail = $examResult ? $examResult->student->email : null;

        $questions = $examResult ? $examResult->questions : null;

        $questionsData = $examResult ? $examResult->questionsData : null;

        $predictedTrack = $examResult ? $examResult->predicted_track : null;

        $secondaryTrack = $examResult ? $examResult->secondary_track : null;

        $aptitude = $examResult ? number_format($examResult->aptitude, 0) : null;

        $trackPercentage = $examResult ? $examResult->track_percentage : null;

        $coreCompetencies = $examResult ? $examResult->core_competencies : null;

        $detailedCompetencyLevels = $examResult ? $examResult->detailed_competencies : null;

        $acc_per_category = $examResult ? $examResult->accuracy_per_category : null;

        $duration_per_category = $examResult ? $examResult->duration_per_category : null;

        $note = $examResult ? $examResult->evaluation_note : null;

        $model_accuracy = $examResult ? $examResult->model_accuracy : null;

        $examID = $id;


        // dd($questionsData, $predictedTrack, $secondaryTrack, $trackPercentage, $coreCompetencies, $detailedCompetencyLevels, $acc_per_category, $duration_per_category, $note, $model_accuracy);
        $redirect = view('retrieve_specific_result', compact(
            'username',
            'useremail',
            'examResult',
            'questions',
            'questionsData',
            'predictedTrack',
            'secondaryTrack',
            'aptitude',
            'trackPercentage',
            'model_accuracy',
            'acc_per_category',
            'duration_per_category',
            'coreCompetencies',
            'detailedCompetencyLevels',
            'note',
            'examID'
        ));

        return $redirect;

    }

    public function sendResultEmail(Request $request)
    {
        // Unique key for this user/action (e.g., based on their session or email)
        $key = 'send-email:' . $request->session()->getId();

        // Check if they've already sent an email in the last 10 seconds
        if (RateLimiter::tooManyAttempts($key, $maxAttempts = 1)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'status' => 'err',
                'message' => "Please wait {$seconds}s before sending again."
            ]);
        }

        // Process the email
        $resultN = session('resultN');
        $userEmail = $resultN['useremail'];
        Mail::to($userEmail)->send(new ExamResultMail($resultN));

        // dd($resultN);

        // Record the attempt for 10 seconds
        RateLimiter::hit($key, $decaySeconds = 10);

        return response()->json([
            'status' => 'success',
            'message' => 'Email sent successfully!'
        ]);
    }
}
