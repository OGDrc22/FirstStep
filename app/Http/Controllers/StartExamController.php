<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StartExamController extends Controller
{
    // public function getView(Request $request) {
    //     $requestData = session('requestData', []);
    //     // dd ($requestData);

    //     $payload = json_encode([
    //         'interests' => $requestData['interest'] ?? []
    //     ]);

    //     // dd ($payload);


    //     $command = "python assets/scripts/gemini.py";

    //     $process = proc_open($command, [
    //         0 => ['pipe', 'r'], // in
    //         1 => ['pipe', 'w'], // out
    //         2 => ['pipe', 'w']  // err
    //     ], $pipes);

    //     if (is_resource($process)) {
    //         fwrite($pipes[0], $payload);
    //         fclose($pipes[0]);

    //         $result = stream_get_contents($pipes[1]);
    //         fclose($pipes[1]);

    //         $error = stream_get_contents($pipes[2]);
    //         fclose($pipes[2]);

    //         proc_close($process);
    //         if ($error) {
    //             echo "<h3>Python STDERR:</h3><pre>$error</pre>";
    //         }

    //         if (!$result) {
    //             return redirect()->route('assessment_entry')->with('creating', 'Failed to generate exam questions. Please try again.');
    //         }


    //         $data = json_decode($result, true);

    //         // dd ($data);

    //         // if (json_last_error() === JSON_ERROR_NONE) {
    //         //     return view('start_exam', compact('data', 'pythonOutput'));
    //         // }

    //         $keys = [];

    //         foreach ($data['data'] as $qSet) {
    //             $keys[] = $qSet[2];
    //         }

    //         session(['answer_keys' => $keys]);

    //         dd($keys);



    //         return view('start_exam', compact('data', 'result'));
    //     }
    //}

    // public function getView() {
    //     return view('start_exam');
    // }

    
}