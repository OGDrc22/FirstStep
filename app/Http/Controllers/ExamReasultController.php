<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExamReasultController extends Controller
{
    public function submitExam(Request $request) {
        $studentAnswer = $request->input('answer');
        $keyAns = session('answer_keys');

        // dd($keyAns, $studentAnswer);
        $payload = json_encode([
            "answers" => $studentAnswer,
            "keys"    => $keyAns
        ]);

        $command = "python assets/scripts/checkerTry.py";

        $process = proc_open($command, [
            0 => ['pipe', 'r'], //in
            1 => ['pipe', 'w'], //out
            2 => ['pipe', 'w'] // err
        ], $pipes);

        if (is_resource($process)) {
            fwrite($pipes[0], $payload);
            fclose($pipes[0]);

            $result = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            proc_close($process);
            if ($error) {
                dd("python error: ", $error);
            }

            $resData = json_decode($result, true);
            return view('exam_result', compact('resData'));
        }


        return view('exam_result', compact('result'));
    }
}
