<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExamReasultController extends Controller
{

//     public function submitExam(Request $request)
// {
//     dd('SUBMIT EXAM HIT');
// }

    public function submitExam(Request $request)
    {
        $studentAnswer = $request->input('answer');
        $keyAns = session('answer_keys');
        $questionData = json_decode($request->input('questionData'), true);

        // Get the student from session / "logged in" from Assessment Entry
        $student = Auth::guard('web')->user();
        // dd(
        // $request->all(),
        //     $request->input('answer'),
        //     $request->input('questionData')
        // );
        if (!$student) {
            // Return JSON if called via AJAX
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You must be logged in to submit the exam.'], 401);
            }
            return back()->withErrors('You must be logged in to submit the exam.');
        }

        // Prepare payload for Python script
        $payload = json_encode([
            "answers" => $studentAnswer,
            "keys" => $keyAns,
            "questionsData" => $questionData
        ]);

        $command = "python assets/scripts/rf.py";
        $process = proc_open($command, [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ], $pipes);

        if (!is_resource($process)) {
            return back()->withErrors('Failed to run exam evaluation script.');
        }

        fwrite($pipes[0], $payload);
        fclose($pipes[0]);

        $result = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        proc_close($process);

        if ($error) {
            \Log::error('Python error', ['error' => $error]);
        }

        $resData = json_decode($result, true);
        if (!$resData || !isset($resData['predicted_track'])) {
            return back()->withErrors('Exam evaluation failed. Please try again.');
        }

        $qData = $resData['qData'];
        $keyAns = $resData['keys'];
        foreach ($qData as $index => &$qItem) {
            if (isset($keyAns[$index])) {
                $qData[$index]['keyAnswer'] = $keyAns[$index];
            }
        }

        $predictedTrack = $resData['predicted_track'];
        $trackPercentage = $resData['track_percentage'];
        $acc_per_category = $resData['accuracy_per_category'];

        // dd($resData);

        // Save results
        DB::transaction(function () use ($student, $predictedTrack, $trackPercentage, $resData, $studentAnswer, $qData) {
            $student->examResults()->create([
                'score' => $resData['score'],
                'predicted_track' => $predictedTrack,
                'track_percentage' => $trackPercentage,
                'accuracy' => $resData['accuracy'],
                'accuracy_per_category' => $resData['accuracy_per_category'],
                'answers' => $studentAnswer,
                'questions' => $qData,
            ]);
        });

        // dd($student, $resData);

        $accuracy = $resData['accuracy'] * 100 . "%";

        return redirect()->route('exam_result', compact('resData', 'qData', 'keyAns', 'predictedTrack', 'trackPercentage', 'accuracy', 'acc_per_category'));
        // return response()->json([
        //     'resData' => $resData,
        //     'qData' => $qData,
        //     'keyAns' => $keyAns,
        //     'predictedTrack' => $predictedTrack,
        //     'trackPercentage' => $trackPercentage,
        //     'accuracy' => $accuracy
        // ]);
    }
    

}
