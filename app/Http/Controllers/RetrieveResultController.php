<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamResult;
use App\Models\student_tb;

class RetrieveResultController extends Controller
{
    public function showForm() {
        return view('retrieve_result');
    }

    public function getResult(Request $request) {
        $request->validate([
            'email' => 'required|email',
        ]);

        $action = $request->input('action');

        

        if ($action === 'latest') {
            $action = $request->input('action');
            $request->validate([
                'email' => 'required|email'
            ]);

            $examResult = ExamResult::whereHas('student', function ($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->latest('id')
            ->first();

            if ($examResult === null) {
                return redirect()->back()
                    ->withErrors(['email' => 'No exam results found for ' . $request->email]);
            }

            // dd($examResult);

            $questions = $examResult ? $examResult->questions : null;
            // dd($questions);
            // $keyAns = $examResult ? array_map(function($q) {
            //     return $q['correct_answer'];
            // }, $questions ?? []) : null;
            $username = $examResult ? $examResult->student->name : null;
            $predictedTrack = $examResult ? $examResult->predicted_track : null;
            $trackPercentage = $examResult ? $examResult->track_percentage : null;
            $acc_per_category = $examResult ? $examResult->accuracy_per_category : null;
            $questionsData = $examResult['questionsData'];
            // dd($keyAns, $examResult);
            // $accuracy = $examResult ? $examResult->accuracy : null;
            if ($examResult) {
                return view('retrieve_result', compact('action', 'examResult', 'username', 'questions', 'predictedTrack', 'trackPercentage', 'acc_per_category', 'questionsData'));
            } else {
                return redirect()->back()->withErrors(['email' => 'No results found for this email.']);
            }
        } elseif ($action === 'all') {

            // TODO Fix
            $action = $request->input('action');
            $request->validate([
                'email' => 'required|email'
            ]);

            $examResult = ExamResult::whereHas('student', function ($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->latest('id')
            ->get();

             if ($examResult->isEmpty()) {
                return redirect()->back()
                    ->withErrors(['email' => 'No exam results found for ' . $request->email]);
            }

            // dd($examResult);
            
            // $username = "TRY";

            // Get username from the first attempt's related student
            $firstAttempt = $examResult->first();
            $username = null;
            if ($firstAttempt && $firstAttempt->student) {
                $username = $firstAttempt->student->name;
            }


            $totalsAcc = [];
            $counts = [];

            foreach ($examResult as $attempt) {
                foreach ($attempt->accuracy_per_category as $track => $accuracy) {

                    // Sum accuracy per track
                    $totalsAcc[$track] = ($totalsAcc[$track] ?? 0) + $accuracy;

                    // Count how many times this track appears
                    $counts[$track] = ($counts[$track] ?? 0) + 1;
                }
            }

            // Average
            $averageAcc = [];
            foreach ($totalsAcc as $track => $total) {
                $averageAcc[$track] = round($total / $counts[$track], 3) * 100;
            }

            asort($averageAcc);          

            // dd($averageAcc);

            $durationTotals = [
                'IT' => 0,
                'CE' => 0,
                'CS' => 0,
                'MMA' => 0,
            ];

            $attemptCount = 0;


            $duration_per_category = [];


            $trackTotals = [
                'CE' => 0,
                'IT' => 0,
                'CS' => 0,
                'MMA' => 0
            ];

            // dd($examResult);
            foreach ($examResult as $attempt) {
                if (!empty($attempt->duration_per_category)) {
                    $attemptCount++;

                    foreach ($attempt->duration_per_category as $track => $duration) {
                        $durationTotals[$track] += $duration;
                    }
                }
            }

            $averageDuration = [];

            if ($attemptCount > 0) {
                foreach ($durationTotals as $track => $total) {
                    $averageDuration[$track] = round($total / $attemptCount, 2);
                }
            }


            $tracks = ['', '', '', ''];
            $scorePerTrack = [];

            foreach (['IT', 'CE', 'CS', 'MMA'] as $track) {
                $accuracy = $averageAcc[$track];               // e.g. 92
                $time     = $averageDuration[$track];          // e.g. 55

                $timeScore = 1 / max($time, 1);                 // lower time = higher score

                $scorePerTrack[$track] =
                    (0.7 * $accuracy) +                         // accuracy weight
                    (0.3 * $timeScore * 100);                   // scaled time
            }


            $recommended = collect($scorePerTrack)
                ->sortDesc()
                ->keys()
                ->first();

            $recommendedTrack = match ($recommended) {
                'IT' => 'Information Technology',
                'CE' => 'Computer Engineering',
                'CS' => 'Computer Science',
                'MMA' => 'Multimedia Arts',
            };




            // dd($examResult, $duration_per_category, $averageDuration);

            if ($examResult) {
                return view('retrieve_result', compact('action', 'examResult', 'username', 'recommendedTrack', 'averageAcc', 'duration_per_category', 'averageDuration'));
            } else {
                return redirect()->back()->withErrors(['email' => 'No results found for this email.']);
            }

        } else {
            return redirect()->back()->withErrors(['action' => 'Invalid action specified.']);
        }
    }

    public function getSpecificExam($id)
    {
        $examResult = ExamResult::with('student')->findOrFail($id);
        $questions = $examResult ? $examResult->questions : null;
        $predictedTrack = $examResult ? $examResult->predicted_track : null;
        $trackPercentage = $examResult ? $examResult->track_percentage : null;

        
        $username = $examResult ? $examResult->student->name : null;

        return view('retrieve_specific_result', compact('username', 'examResult', 'questions', 'predictedTrack', 'trackPercentage'));
        
    }
}
