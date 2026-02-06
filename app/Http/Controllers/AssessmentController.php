<?php

namespace App\Http\Controllers;

use App\Models\ExamJob;
use Illuminate\Http\Request;
use App\Models\student_tb;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AssessmentController extends Controller
{
    public function showAssessmentEntryForm() {
        return view('assessment_entry');
    }
    public function generateExam(Request $request)  {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $requestData = $request->all();
        session(['requestData' => $requestData]);


        // $miniTest = $request->minitest; // [ "0" ]
        $miniTest = json_decode($request->minitest_json, true) ?? [];
        $score = 0;

        foreach ($miniTest as $answer) {
            if (
                isset($answer['correct']) &&
                $answer['correct'] !== null &&
                isset($answer['selected']) &&
                (int)$answer['selected'] === (int)$answer['correct']
            ) {
                $score++;
            }
        }

        
        $otherMiniTest = collect($miniTest)->firstWhere('interest', 'other');
        
        $rawInterests = array_map('trim', explode(',', $request->input('interest', '')));

        // remove "other"
        $interests = array_values(array_filter($rawInterests, fn ($i) => $i !== 'other'));

        $otherContext = $otherMiniTest
            ? 'User selected an additional unspecified interest and answered a general aptitude question.'
            : null;


        $miniTestSummary = [];

        foreach ($miniTest as $answer) {
            $miniTestSummary[] = [
                'interest' => $answer['interest'],
                'question_id' => $answer['question_id'],
                'is_correct' => (
                    $answer['correct'] !== null &&
                    (int)$answer['selected'] === (int)$answer['correct']
                )
            ];
        }

        $interestSignals = [];

        foreach ($miniTestSummary as $item) {
            $key = $item['interest'];

            if (!isset($interestSignals[$key])) {
                $interestSignals[$key] = [
                    'mini_test_correct' => 0,
                    'mini_test_total' => 0
                ];
            }

            if ($item['is_correct'] !== null) {
                $interestSignals[$key]['mini_test_total']++;
                if ($item['is_correct']) {
                    $interestSignals[$key]['mini_test_correct']++;
                }
            }
        }

        $payloadInterests = [];

        foreach ($interestSignals as $interest => $mini) {
            $payloadInterests[] = [
                'interest' => $interest,
                'skills' => $request->skills[$interest] ?? null,
                'mini_test' => [
                    'correct' => $mini['mini_test_correct'],
                    'total'   => $mini['mini_test_total'],
                ],
                'type' => $interest === 'other' ? 'generic' : 'known'
            ];
        }


        // dd($interestSignals, $miniTestSummary, $payloadInterests);

        // Check if student exists
        $student = student_tb::firstOrCreate(
            ['email' => $request->email],
            ['name' => $request->name]
        );

        // Log the student in
        Auth::guard('web')->login($student);        

        try {
            $job = ExamJob::create([
                'student_id' => $student->id,
                'payload' => $payloadInterests,
                'status' => 'pending',
            ]);
    

            // WINDOWS-SAFE background execution
            $pythonPath = 'C:\Users\admin\AppData\Local\Programs\Python\Python312\python.exe';
            $scriptPath = base_path('public/assets/scripts/gemini.py');
            $command = sprintf(
                'start "" /B "%s" -u "%s" %d',
                $pythonPath,
                $scriptPath,
                $job->id,
            );

            // ⬇️ RUN IN BACKGROUND (THIS IS CRITICAL)
            pclose(popen($command, "r"));
            // $command = "\"$pythonPath\" \"$scriptPath\" {$job->id}";
            // exec($command);


            return response()->json([
                'status' => 'started',
                'job_id' => $job->id
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // public function examStatus() {
    //     $file = storage_path('app/exam_' . session()->getId() . '.json');

    //     if (file_exists($file)) {
    //         $data = json_decode(file_get_contents($file), true);

    //         return response()->json($data);  // returns current status
    //     }

    //     // file not created yet
    //     return response()->json([
    //         'status' => 'processing',
    //         'message' => 'Exam generation is starting...'
    //     ]);
    // }




    public function showExam(ExamJob $job){

        $student = Auth::guard('web')->user();
      
        if ($job->student_id !== $student->id) {
            abort(403, 'Unauthorized access to this exam.');
        }

        if ($job->status !== 'done') {
            abort(404, 'Exam is not ready yet.');
        }

        $questions = $job->output;
        // dd($questions);

        
        if (!is_array($questions)) {
            abort(500, 'Exam output is missing or invalid.');
        }
        
        $keys = [];

        foreach ($questions['questions'] as $qSet) {
            $keys[] = $qSet['answer'];
        }

        session(['answer_keys' => $keys]);


        // dd($keys, $questions);

        // OPTIONAL: cleanup after loading
        // unlink($file);

        return view('exam_page', [
            'data' => [
                'data' => $questions
            ]
        ]);
    }



}
