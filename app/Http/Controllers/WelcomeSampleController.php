<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeSampleController extends Controller
{
    public function runPython() {
        $pythonOutput = shell_exec("python assets/scripts/a.py");
        $data = json_decode($pythonOutput, true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            return view('welcome', [
                'output' => [
                    'success' => $data['status'] === 'success',
                    'error' => $data['error'],
                    'data' => $data['data'],
                    'raw' => $pythonOutput
                ]
            ]);
        }
        
        return view('welcome', [
            'output' => [
                'success' => false,
                'error' => 'Failed to parse Python output',
                'data' => null,
                'raw' => $pythonOutput
            ]
        ]);
    }
}
