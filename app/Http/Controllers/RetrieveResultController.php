<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamResult;

class RetrieveResultController extends Controller
{
    public function showForm() {
        return view('retrieve_result');
    }

    public function getResult(Request $request) {
        $request->validate([
            'email' => 'required|email',
        ]);

        $action = $request->input('action');

        

        if ($action === 'latest') {
            $action = $request->input('action');
            $examResult = ExamResult::whereHas('student', function ($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->orderBy('id', 'desc')
            ->first();

            // dd($examResult);

            $qData = $examResult ? $examResult->questions : null;
            // $keyAns = $examResult ? array_map(function($q) {
            //     return $q['correct_answer'];
            // }, $qData ?? []) : null;
            $username = $examResult ? $examResult->student->name : null;
            $predictedTrack = $examResult ? $examResult->predicted_track : null;
            $trackPercentage = $examResult ? $examResult->track_percentage : null;
            $accuracy = $examResult ? $examResult->accuracy : null;
            if ($examResult) {
                return view('retrieve_result', compact('action', 'examResult', 'username', 'qData', 'predictedTrack', 'trackPercentage', 'accuracy'));
            } else {
                return redirect()->back()->withErrors(['email' => 'No results found for this email.']);
            }
        } elseif ($action === 'all') {
            $action = $request->input('action');
            $examResult = ExamResult::whereHas('student', function ($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->orderBy('id', 'desc')
            ->get();

            // dd($examResult);

            $username = $examResult->first()->student->name;

            if ($examResult) {
                return view('retrieve_result', compact('action', 'examResult', 'username'));
            } else {
                return redirect()->back()->withErrors(['email' => 'No results found for this email.']);
            }

        } else {
            return redirect()->back()->withErrors(['action' => 'Invalid action specified.']);
        }
    }

    // public function getAllResult(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //     ]);


    //     if ($examResult) {
    //         return view('display_result', ['examResult' => $examResult]);
    //     } else {
    //         return redirect()->back()->withErrors(['email' => 'No results found for this email.']);
    //     }
    // }
}
