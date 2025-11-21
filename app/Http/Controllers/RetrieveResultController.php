<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RetrieveResultController extends Controller
{
    public function showForm() {
        return view('retrieve_result');
    }

    public function getResult() {
        // Logic to retrieve and return the result
        $sampleResult = [
            'name' => 'John Doe',
            'score' => '85% at Logical Thinking, 90% at Problem Solving, 20% at Creativity, 70% at Teamwork, 20% at Communication',
            'status' => 'Passed',
            'recommendation' => 'You are very suitable for computer science, beacause you have a strong logical thinking ability.'
        ];
        return view('retrieve_result', compact('sampleResult'));
    }
}
