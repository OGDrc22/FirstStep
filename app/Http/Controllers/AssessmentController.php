<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\student_tb;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AssessmentController extends Controller
{
    public function showAssessmentEntryForm() {
        return view('assessment_entry'); // form with name + email
    }

    public function login_data(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        // dd ($request->all());

        $requestData = $request->all();
        session(['requestData' => $requestData]);

        // Check if student exists
        $student = student_tb::firstOrCreate(
            ['email' => $request->email],
            ['name' => $request->name]
        );

        // Log the student in
        Auth::guard('web')->login($student);

        return redirect()->route('start-exam');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('assessment_entry');
    }



    public function startExam(Request $request) {
        return view('assessment_entry');
    }


    public function generateExam(Request $request)  {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        // dd ($request->all());

        $requestData = $request->all();
        session(['requestData' => $requestData]);

        // Check if student exists
        $student = student_tb::firstOrCreate(
            ['email' => $request->email],
            ['name' => $request->name]
        );

        // Log the student in
        Auth::guard('web')->login($student);

        

        try {
            $outputFile = storage_path(
                'app/exam_' . session()->getId() . '.json'
            );
            // if (!file_exists($outputFile)) {
            //     return response()->json([
            //         'status' => 'processing',
            //         'message' => 'Exam generation in progress...22'
            //     ]);
            // }

            $payloadFile = storage_path(
                'app/payload_' . session()->getId() . '.json' 
            );

            file_put_contents($payloadFile, json_encode([
                'interests' => explode(',', $request->input('interest', ''))])
            );
    

            // WINDOWS-SAFE background execution
            $pythonPath = 'C:\Users\admin\AppData\Local\Programs\Python\Python312\python.exe';
            $scriptPath = base_path('public/assets/scripts/gemini.py');
            $command = sprintf(
                'start "" /B "%s" "%s" "%s" "%s"',
                $pythonPath,
                $scriptPath,
                $payloadFile,
                $outputFile
            );

            // ⬇️ RUN IN BACKGROUND (THIS IS CRITICAL)
            pclose(popen($command, "r"));

            return response()->json([
                'status' => 'started'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function examStatus() {
        $file = storage_path('app/exam_' . session()->getId() . '.json');

        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);

            return response()->json($data);  // returns current status
        }

        // file not created yet
        return response()->json([
            'status' => 'processing',
            'message' => 'Exam generation is starting...'
        ]);
    }




    public function showExam(){

        $student = Auth::guard('web')->user();
        // dd($student);
        $file = storage_path('app/exam_' . session()->getId() . '.json');

        if (!file_exists($file)) {
            abort(404, 'No exam data available.');
        }

        $data = json_decode(file_get_contents($file), true);
        
        $keys = [];

        foreach ($data['data'] as $qSet) {
            $keys[] = $qSet[2];
        }

        session(['answer_keys' => $keys]);

        // dd($keys, $data);

        // OPTIONAL: cleanup after loading
        // unlink($file);

        return view('exam_page', compact('data'));
    }



}