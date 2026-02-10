<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamJob;
class ExamReasultController extends Controller
{

//     public function submitExam(Request $request)
// {
//     dd('SUBMIT EXAM HIT');
// }

    public function submitExam(Request $request)
    {
        // dd("hit");
        $studentAnswer = $request->input('answer');
        $keyAns = session('answer_keys');
        $questionData = json_decode($request->input('questionData'), true);

        // dd($studentAnswer, $keyAns, $questionData);
        // Get the student from session / "logged in" from Assessment Entry
        $student = Auth::guard('web')->user();

        if (!$student) {
            // Return JSON if called via AJAX
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You must be logged in to submit the exam.'], 401);
            }
            return back()->withErrors('You must be logged in to submit the exam.');
        }

        // dd($student);
        $job = ExamJob::findOrFail($request->job_id);
        $assessmentPayload = $job->payload;
        // dd($job, $assessmentPayload);


        $qData = [];
        
        foreach ($questionData as $i => $q) {
            $qData[$i] = $q['questionText'];
        }

        // dd($qData);


        $totalQuestions = count($keyAns);
        $correct = 0;

        foreach ($studentAnswer as $i => $ans) {
            if (isset($keyAns[$i]) && (int)$ans === (int)$keyAns[$i]) {
                $correct++;
            }
        }

        $accuracy = $totalQuestions > 0
            ? $correct / $totalQuestions
            : 0;


        // dd($correct);

        $acc_per_category = [];

        foreach ($questionData as $i => $q) {
            $cat = $q['category'] ?? 'unknown';

            if (!isset($acc_per_category[$cat])) {
                $acc_per_category[$cat] = ['correct' => 0, 'total' => 0];
            }

            $acc_per_category[$cat]['total']++;

            if (
                isset($studentAnswer[$i], $keyAns[$i]) &&
                (int)$studentAnswer[$i] === (int)$keyAns[$i]
            ) {
                $acc_per_category[$cat]['correct']++;
            }
        }

        $duration_per_category = [];

        foreach ($questionData as $q) {
            $cat = $q['category'] ?? 'unknown';
            $dur = $q['duration'] ?? 0;

            $duration_per_category[$cat] =
                ($duration_per_category[$cat] ?? 0) + $dur;
        }


        foreach ($acc_per_category as &$c) {
            $c = $c['total'] > 0 ? $c['correct'] / $c['total'] : 0;
        }


        // Prepare payload for Python script
        $payload = json_encode([
            'assessment' => $assessmentPayload,
            'exam_metrics' => [
                'score' => $correct,
                'accuracy' => $accuracy,
                'accuracy_per_category' => $acc_per_category,
                'duration_per_category' => $duration_per_category
            ]
        ]);


        // dd($payload);


        $command = "python assets/scripts/main_algo_try.py";
        // dd($command);
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



        // output
        $resData = json_decode($result, true);
        if (!$resData || !isset($resData['predicted_track'])) {
            return back()->withErrors('Exam evaluation failed. Please try again.');
        }

        // dd($questionData);

        // $correct
        foreach ($questionData as $index => &$qItem) {
            // dd($keyAns[$index], $qItem['answer']);
            if (isset($keyAns[$index])) {
                $qItem['keyAnswer'] = $keyAns[$index];
                // dd($qItem);
            }
        }

        // dd($questionData, $keyAns);
        $predictedTrack = $resData['predicted_track'];
        $trackPercentage = $resData['track_percentage'];

        $accuracy = $resData['sys_accuracy'];

        // dd($duration_per_category);
        // dd($accuracy);

        // Save results
        DB::transaction(function () use ($student, $correct, $predictedTrack, $trackPercentage, $accuracy, $duration_per_category, $studentAnswer, $qData, $acc_per_category) {
            $student->examResults()->create([
                'score' => $correct,
                'predicted_track' => $predictedTrack,
                'track_percentage' => $trackPercentage,
                'accuracy' => $accuracy,
                'accuracy_per_category' => $acc_per_category,
                'duration_per_category' => $duration_per_category,
                'answers' => $studentAnswer,
                'questions' => $qData,
            ]);
        });

        // dd($acc_per_category);
        $accuracy = $resData['sys_accuracy'] * 100 . "%";

        return view('exam_result', compact('resData', 'qData', 'questionData', 'keyAns', 'predictedTrack', 'trackPercentage', 'accuracy', 'acc_per_category', 'correct', 'totalQuestions'));
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
