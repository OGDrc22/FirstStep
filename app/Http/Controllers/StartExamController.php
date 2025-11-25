<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StartExamController extends Controller
{
    public function getView() {
        $pythonOutput = shell_exec("python assets/scripts/gemini.py");
        $data = json_decode($pythonOutput, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return view('start_exam', compact('data', 'pythonOutput'));
        }

        $keys = [];

        foreach ($data['data'] as $qSet) {
            $keys[] = $qSet[2];
        }

        session(['answer_keys' => $keys]);



        return view('start_exam', compact('data', 'pythonOutput'));
    }

    // public function getView() {
    //     return view('start_exam');
    // }

    
}