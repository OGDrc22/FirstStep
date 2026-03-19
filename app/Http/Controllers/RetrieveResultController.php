<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamResult;
use App\Models\student_tb;
use App\Services\RecommendationService;

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
            $request->validate([
                'email' => 'required|email'
            ]);

            $examResult = ExamResult::whereHas('student', function ($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->latest('id')
            ->first();

            if ($examResult === null) {
                return redirect()->back()
                    ->withErrors(['email' => 'No exam results found for ' . $request->email]);
            }

            // dd($examResult);

            $questions = $examResult ? $examResult->questions : null;
            // dd($questions);
            // $keyAns = $examResult ? array_map(function($q) {
            //     return $q['correct_answer'];
            // }, $questions ?? []) : null;
            $username = $examResult ? $examResult->student->name : null;

            $questionsData = $examResult ? $examResult->questionsData : null;

            $predictedTrack = $examResult ? $examResult->predicted_track : null;

            $secondaryTrack = $examResult ? $examResult->secondary_track : null;
        
            $trackPercentage = $examResult ? $examResult->track_percentage : null;

            $coreCompetencies = $examResult ? $examResult->core_competencies : null;

            $detailedCompetencyLevels = $examResult ? $examResult->detailed_competencies : null;

            $acc_per_category = $examResult ? $examResult->accuracy_per_category : null;

            $duration_per_category = $examResult ? $examResult->duration_per_category : null;

            $note = $examResult ? $examResult->evaluation_note : null;

            $model_accuracy = $examResult ? $examResult->model_accuracy : null;

            

            // dd($questionsData, $predictedTrack, $secondaryTrack, $trackPercentage, $coreCompetencies, $detailedCompetencyLevels, $acc_per_category, $duration_per_category, $note, $model_accuracy);
            $redirect = view('retrieve_result', compact(
                'action',
                'username',
                'examResult',
                'questions',
                'questionsData',
                'predictedTrack',
                'secondaryTrack',
                'trackPercentage',
                'model_accuracy',
                'acc_per_category',
                'duration_per_category',
                'coreCompetencies',
                'detailedCompetencyLevels',
                'note'
            ));

            if ($examResult) {
                return $redirect;
            } else {
                return redirect()->back()->withErrors(['email' => 'No results found for this email.']);
            }
        } elseif ($action === 'all') {

            $action = $request->input('action');
            $request->validate([
                'email' => 'required|email'
            ]);

            $examResult = ExamResult::whereHas('student', function ($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->latest('id')
            ->get();

             if ($examResult->isEmpty()) {
                return redirect()->back()
                    ->withErrors(['email' => 'No exam results found for ' . $request->email]);
            }

            $firstAttempt = $examResult->first();
            $username = null;
            if ($firstAttempt && $firstAttempt->student) {
                $username = $firstAttempt->student->name;
            }

            $service = new RecommendationService();

            $result = $service->analyzeAllAttempts($examResult);

            $recommendedTrack = $result['recommended_track'];
            $averageAcc = $result['averageAcc'];
            $averageDuration = $result['averageDuration'];
            $trackPercentage = $result['trackPercentage'];
            
            // dd($examResult);

            $dateAttmpt = [];

            foreach ($examResult as $attempt) {
                $dateAttmpt[] = $attempt->created_at->format('Y-m-d H:i:s');
                
            }
            // $trackPercentageA = [];

            // foreach ($examResult as $attempt => $data) {
            //     $trackPercentageA[$attempt]['percentage'] = $attempt->track_percentage;
            // }
            // dd($trackPercentage);
            
            return view('retrieve_result', compact('action', 'username', 'recommendedTrack', 'averageAcc', 'averageDuration', 'examResult','trackPercentage', 'dateAttmpt'));

        } else {
            return redirect()->back()->withErrors(['action' => 'Invalid action specified.']);
        }
    }

    public function getSpecificExam($id)
    {
        $examResult = ExamResult::with('student')->findOrFail($id);

        $username = $examResult ? $examResult->student->name : null;
        
        $questions = $examResult ? $examResult->questions : null;
    
        $questionsData = $examResult ? $examResult->questionsData : null;

        $predictedTrack = $examResult ? $examResult->predicted_track : null;

        $secondaryTrack = $examResult ? $examResult->secondary_track : null;
    
        $trackPercentage = $examResult ? $examResult->track_percentage : null;

        $coreCompetencies = $examResult ? $examResult->core_competencies : null;

        $detailedCompetencyLevels = $examResult ? $examResult->detailed_competencies : null;

        $acc_per_category = $examResult ? $examResult->accuracy_per_category : null;

        $duration_per_category = $examResult ? $examResult->duration_per_category : null;

        $note = $examResult ? $examResult->evaluation_note : null;

        $model_accuracy = $examResult ? $examResult->model_accuracy : null;

        
        // dd($questionsData, $predictedTrack, $secondaryTrack, $trackPercentage, $coreCompetencies, $detailedCompetencyLevels, $acc_per_category, $duration_per_category, $note, $model_accuracy);
        $redirect = view('retrieve_specific_result', compact(
            'username',
            'examResult',
            'questions',
            'questionsData',
            'predictedTrack',
            'secondaryTrack',
            'trackPercentage',
            'model_accuracy',
            'acc_per_category',
            'duration_per_category',
            'coreCompetencies',
            'detailedCompetencyLevels',
            'note'
        ));

        return $redirect;
        
    }
}
