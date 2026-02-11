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
        $exam_qd = json_decode($request->input('questionData'), true);

        // dd($exam_qd);

        // dd($studentAnswer, $keyAns, $exam_qd);
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


        $questions = [];
        
        foreach ($exam_qd as $i => $q) {
            $questions[$i] = $q['questionText'];
        }

        // dd($questions);


        $totalQuestions = count($keyAns);
        $correct = 0;

        foreach ($studentAnswer as $i => $ans) {
            if (isset($keyAns[$i]) && $ans === $keyAns[$i]) {
                $correct++;
            }
        }
        // dd($studentAnswer, $keyAns, $correct);

        $accuracy = $totalQuestions > 0
            ? $correct / $totalQuestions
            : 0;


        // dd($correct);

        $acc_per_category = [];

        foreach ($exam_qd as $i => $q) {
            $cat = $q['category'] ?? 'unknown';

            if (!isset($acc_per_category[$cat])) {
                $acc_per_category[$cat] = ['correct' => 0, 'total' => 0];
            }

            $acc_per_category[$cat]['total']++;

            if (
                isset($studentAnswer[$i], $keyAns[$i]) &&
                $studentAnswer[$i] === $keyAns[$i]
            ) {
                $acc_per_category[$cat]['correct']++;
            }
        }
        
        //TODO FIX
        foreach ($acc_per_category as &$c) {
            $c = $c['total'] > 0 ? $c['correct'] / $c['total'] : 0;
        }
        
        $duration_per_category = [];

        foreach ($exam_qd as $q) {
            $cat = $q['category'] ?? 'unknown';
            $dur = $q['duration'] ?? 0;

            // dd($cat, $dur);
            $duration_per_category[$cat] =
                ($duration_per_category[$cat] ?? 0) + $dur;
        }


        


    
        $questionsData = [];
        foreach ($exam_qd as $i => $ex) {
            $questionsData[] = [
                'answer' => $ex['answer'],
                'duration' => $ex['duration'] ?? null, 
                'keyAns' => $keyAns[$i] ?? null
                ];
        }

        // dd($questionsData, $exam_qd, $keyAns);


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

        // dd($exam_qd);

        // $correct
        foreach ($exam_qd as $index => &$qItem) {
            // dd($keyAns[$index], $qItem['answer']);
            if (isset($keyAns[$index])) {
                $qItem['keyAnswer'] = $keyAns[$index];
                // dd($qItem);
            }
        }

        // dd($exam_qd, $keyAns);
        $predictedTrack = $resData['predicted_track'];
        $trackPercentage = $resData['track_percentage'];

        $accuracy = $resData['sys_accuracy'];

        // dd($duration_per_category);
        // dd($accuracy);

        // dd($keyAns);
        // Save results
        DB::transaction(function () use ($student, $correct, $predictedTrack, $trackPercentage, $accuracy, $duration_per_category, $studentAnswer, $questions, $questionsData, $acc_per_category) {
            $student->examResults()->create([
                'score' => $correct,
                'predicted_track' => $predictedTrack,
                'track_percentage' => $trackPercentage,
                'accuracy' => $accuracy,
                'accuracy_per_category' => $acc_per_category,
                'duration_per_category' => $duration_per_category,
                'answers' => $studentAnswer,
                'questionsData' => $questionsData,
                'questions' => $questions,
            ]);
        });

        // dd($acc_per_category);
        $accuracy = $resData['sys_accuracy'] * 100 . "%";

        return view('exam_result', compact('resData', 'questions', 'questionsData', 'keyAns', 'predictedTrack', 'trackPercentage', 'accuracy', 'acc_per_category', 'correct', 'totalQuestions', 'duration_per_category'));
        // return response()->json([
        //     'resData' => $resData,
        //     'questions' => $questions,
        //     'keyAns' => $keyAns,
        //     'predictedTrack' => $predictedTrack,
        //     'trackPercentage' => $trackPercentage,
        //     'accuracy' => $accuracy
        // ]);
    }
    

}
