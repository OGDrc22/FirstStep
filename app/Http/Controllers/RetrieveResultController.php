<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamResult;
use App\Models\student_tb;
use App\Services\RetrieveResultService;

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

            $useremail = $examResult ? $examResult->student->email : null;

            $questionsData = $examResult ? $examResult->questionsData : null;

            $predictedTrack = $examResult ? $examResult->predicted_track : null;

            $secondaryTrack = $examResult ? $examResult->secondary_track : null;

            $aptitude = $examResult ? number_format($examResult->aptitude, 0) : null;
        
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
                'useremail',
                'examResult',
                'questions',
                'questionsData',
                'predictedTrack',
                'secondaryTrack',
                'aptitude',
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

            $service = new RetrieveResultService();

            $result = $service->analyzeAllAttempts($examResult);
            // dd($result);

            $recommendedTrack = $result['recommended_track'];
            $secondaryTrack = $result['second_recommendation'];
            $averageAcc = $result['averageAcc'];
            $averageDuration = $result['averageDuration'];
            $trackPercentage = $result['rawTrackPercentage'];
            $computedTrackPercentage = $result['computedTrackPercentage'];
            $note = $result['note'];

            $dateAttmpt = [];

            foreach ($examResult as $attempt) {
                $dateAttmpt[] = $attempt->created_at->format('Y-m-d H:i:s');
                
            }
            $examResult = ExamResult::whereHas('student', function ($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->latest('id')
            ->take(4)
            ->get();
            // $trackPercentageA = [];

            // foreach ($examResult as $attempt => $data) {
            //     $trackPercentageA[$attempt]['percentage'] = $attempt->track_percentage;
            // }
            // dd($averageAcc);
            
            return view('retrieve_result', compact('action', 'username', 'recommendedTrack', 'note', 'secondaryTrack', 'averageAcc', 'averageDuration', 'examResult', 'computedTrackPercentage', 'trackPercentage', 'dateAttmpt'));

        } else {
            return redirect()->back()->withErrors(['action' => 'Invalid action specified.']);
        }
    }

    public function getSpecificExam($id)
    {
        $examResult = ExamResult::with('student')->findOrFail($id);

        $username = $examResult ? $examResult->student->name : null;

        $useremail = $examResult ? $examResult->student->email : null;
        
        $questions = $examResult ? $examResult->questions : null;
    
        $questionsData = $examResult ? $examResult->questionsData : null;

        $predictedTrack = $examResult ? $examResult->predicted_track : null;

        $secondaryTrack = $examResult ? $examResult->secondary_track : null;

        $aptitude = $examResult ? number_format($examResult->aptitude, 0) : null;
    
        $trackPercentage = $examResult ? $examResult->track_percentage : null;

        $coreCompetencies = $examResult ? $examResult->core_competencies : null;

        $detailedCompetencyLevels = $examResult ? $examResult->detailed_competencies : null;

        $acc_per_category = $examResult ? $examResult->accuracy_per_category : null;

        $duration_per_category = $examResult ? $examResult->duration_per_category : null;

        $note = $examResult ? $examResult->evaluation_note : null;

        $model_accuracy = $examResult ? $examResult->model_accuracy : null;

        $examID = $id;

        
        // dd($questionsData, $predictedTrack, $secondaryTrack, $trackPercentage, $coreCompetencies, $detailedCompetencyLevels, $acc_per_category, $duration_per_category, $note, $model_accuracy);
        $redirect = view('retrieve_specific_result', compact(
            'username',
            'useremail',
            'examResult',
            'questions',
            'questionsData',
            'predictedTrack',
            'secondaryTrack',
            'aptitude',
            'trackPercentage',
            'model_accuracy',
            'acc_per_category',
            'duration_per_category',
            'coreCompetencies',
            'detailedCompetencyLevels',
            'note',
            'examID'
        ));

        return $redirect;
        
    }
}
