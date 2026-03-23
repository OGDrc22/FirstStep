<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamJob;
use App\Services\ExamResultService;
class ExamReasultController extends Controller
{
    public function submitExam(Request $request)
    {
        // dd("hit");
        $studentAnswer = $request->input('answer');
        $keyAns = session('answer_keys');
        $keyAnsText = session('answer_keys_text');
        $exam_qd = json_decode($request->input('questionData'), true);

        // dd($exam_qd, $keyAns);
        $student = Auth::guard('web')->user();

        $service = new ExamResultService();
        $service->validateStudent($student, $request);

        $job = ExamJob::findOrFail($request->job_id);
        $assessmentPayload = $job->payload;
        
        $username = $student->name;

        $useremail = $student->email;

        $questions = [];
        
        foreach ($exam_qd as $i => $q) {
            $questions[$i] = $q['questionText'];
        }


        [$rawAbility_Interest, $exam_qd] = $service->getAbility_Interest($exam_qd, $studentAnswer, $keyAns);

        // dd($rawAbility_Interest, $exam_qd);

        $aptitude = $service->getAptitude($exam_qd, $rawAbility_Interest);
        // dd($aptitude);

        $competencyOrder = [
            "logical_reasoning",
            "syntax_analysis",
            "algorithmic_thinking",
            "hardware_systems",
            "networking_systems",
            "system_organization",
            "digital_creativity",
            "ui_design",
            "attention_to_detail",
            "problem_solving"
        ];
    
        // dd($exam_qd);
        $competencyScores = $service->calculateCoreCopetency($exam_qd, $competencyOrder);
        // dd($competencyScores);

        $features = $service->calculateCompetencyScore($competencyScores);

        $vector = [];

        foreach ($competencyOrder as $comp) {
            $vector[] = $features[$comp] ?? 0;
        }

        // dd($features);

        $avg_time = [];
        foreach ($competencyScores as $i => $c) {
            $tl = $competencyScores[$i]['total'];
            $time = $competencyScores[$i]['time'];
            $avg_time[$i] = $tl > 0 ? $time / $tl : 0;
        }

        $baseline = array_sum($avg_time) / count($competencyScores);

        $cognitive = [];
        $speedValues = [];
        $confidenceValues = [];

        foreach ($competencyScores as $name => $comp) {

            $ttl = $comp['total'];
            $accuracy = $ttl > 0 ? $comp['correct'] / $ttl : 0;
            $avgTime = $ttl > 0 ? $comp['time'] / $ttl : 0;

            $speed =  $baseline / max($avgTime, 1);

            $cognitive[$name] = $service->getCognitiveLevel($accuracy, $avgTime, $baseline);
            $speedValues[$name . '_speed'] = $speed;
            $confidenceValues[$name . '_confidence'] = $accuracy * $speed;
        }


        $speed_per_competency = [
            "logical_reasoning_speed",
            "syntax_analysis_speed",
            "algorithmic_thinking_speed",
            "hardware_systems_speed",
            "networking_systems_speed",
            "system_organization_speed",
            "digital_creativity_speed",
            "ui_design_speed",
            "attention_to_detail_speed",
            "problem_solving_speed"
        ];

        foreach ($speed_per_competency as $sp) {
            $vector[] = $speedValues[$sp] ?? 0;
        }

        $speed_by_competency = [];
        foreach ($competencyScores as $i => $comp) {
            $speed_by_competency[$i] = $speedValues[$i . '_speed'];
        }
        foreach ($confidenceValues as $c) {
            $vector[] = $c;
        }


        $acc_per_category = [];

        foreach ($exam_qd as $i => $q) {
            $cat = $q['category'] ?? 'unknown';

            if (!isset($acc_per_category[$cat])) {
                $acc_per_category[$cat] = ['correct' => 0, 'total' => 0];
            }

            $acc_per_category[$cat]['total']++;

            if (
                isset($studentAnswer[$i], $keyAns[$i]) &&
                $studentAnswer[$i] === $keyAns[$i]
            ) {
                $acc_per_category[$cat]['correct']++;
            }
        }
        
        foreach ($acc_per_category as &$c) {
            $c = $c['total'] > 0 ? $c['correct'] / $c['total'] : 0;
        }


        $trackCategory = [
            'Information Technology',
            'Computer Science',
            'Computer Engineering',
            'Multimedia Arts'
        ];


        foreach ($trackCategory as $t) {
             $vector[] = $rawAbility_Interest[$t]['interest'] ?? 0;
        }

        foreach ($trackCategory as $t) {
            $vector[] = $acc_per_category[$t] ?? 0;
        }
        
        $duration_per_category = [];

        foreach ($exam_qd as $q) {
            $cat = $q['category'] ?? 'unknown';
            $dur = $q['duration'] ?? 0;

            // dd($cat, $dur);
            $duration_per_category[$cat] =
                ($duration_per_category[$cat] ?? 0) + $dur;
        }


        $total_cat_duration = max(array_sum($duration_per_category), 1);

        foreach ($trackCategory as $t) {
            $vector[] = $duration_per_category[$t] / $total_cat_duration;
        }
        // --- ARANGEMENT ---
        // accuracy_by_competency (10)
        // speed_by_competency (10)
        // confidence (10)
        // category_interest (4)
        // category_accuracy (4)
        // category_time_ratio (4)

        // dd($vector);

    
        $questionsData = [];
        foreach ($exam_qd as $i => $ex) {
            // dd($ex['answer']);
            $questionsData[] = [
                'answer' => $ex['answer'],
                'duration' => $ex['duration'] ?? null, 
                'keyAns' => [$keyAns[$i] ?? null, $keyAnsText[$i] ?? null]
                ];
        }

        // dd($competencyOrder, $vector, $features, $speed_by_competency, $trackCategory);
        // dd($vector, $features, $speed_by_competency, $confidenceValues, $acc_per_category, $duration_per_category);

        $maxConfidence = max($confidenceValues);

        $normalizedConfidence = [];

        foreach ($confidenceValues as $name => $value) {
            $normalizedConfidence[$name] = $maxConfidence ? ($value / $maxConfidence) : 0;
        }


        $coreCompetencies = $service->getCoreCompetency($normalizedConfidence, $competencyScores);


        $detailedCompetencyLevels = [];

        foreach ($normalizedConfidence as $name => $score) {
            
            $comp = str_replace('_confidence', '', $name);
            if ($competencyScores[$comp]['total'] == 0) {
                $detailedCompetencyLevels[$comp] = [
                    'score' => $score,
                    'level' => "Not Assessed"
                ];
            } else {
                $detailedCompetencyLevels[$comp] = [
                    'score' => $score,
                    'level' => $service->getLevel($score)
                ];
            }
        }


        // Prepare payload for Python script
        $payload = json_encode([
            'assessment' => $assessmentPayload,
            'features' => $vector
        ]);

        // dd($payload);

        $resData = $service->runMainAlgorithm($payload);


        // dd($exam_qd, $keyAns);
        $predictedTrack = $resData['predicted_track'];
        $predictedTrack = [
            'track' => $predictedTrack[0],
            'percentage' => $predictedTrack[1]
        ];
        $secondaryTrack = $resData['secondary_track'];
        $secondaryTrack = [
            'track' => $secondaryTrack[0],
            'percentage' => $secondaryTrack[1]
        ];
        $trackPercentage = $resData['track_percentage'];

        foreach ($trackPercentage as $track => $track_p) {
            $trackPercentage[$track] = [
                'track' => $track,
                'percentage' => $track_p
            ];
        }

        $model_accuracy = $resData['model_accuracy'];

        $tPercentage = [];
        foreach ($trackPercentage as $track => $data) {
            $tPercentage[$track] = $data['percentage'];
        }

        $note = $service->generateCounselorNote($tPercentage, $coreCompetencies);

        $probabilitiesArray = $resData['probabilities'];
        $probabilities = number_format(collect($probabilitiesArray)->max() * 100, 2);

        // dd($keyAns);
        // Save results
        $correct = 0;
        // $service->saveToDB(
        //     $student,
        //     $correct,
        //     $predictedTrack,
        //     $secondaryTrack,
        //     $trackPercentage,
        //     $coreCompetencies,
        //     $detailedCompetencyLevels,
        //     $note,
        //     $aptitude,
        //     $duration_per_category,
        //     $questions,
        //     $questionsData,
        //     $acc_per_category
        // );

        // dd($acc_per_category);
        // $accuracy = $model_accuracy * 100 . "%";
        $model_accuracy = 0;


        // FEEDBACK DATA

        $feedback = null;
        

        if ($request->input('feedback-input')) {
            $feedback = $request->input('feedback-input');
            // dd($feedback);

            $service->saveFeedback($student, $request, $feedback);
        }

        $redirect = view('exam_result', compact(
            'username',
            'useremail',
            'resData',
            'questions',
            'questionsData',
            'keyAns',
            'keyAnsText',
            'predictedTrack',
            'secondaryTrack',
            'aptitude',
            'trackPercentage',
            'model_accuracy',
            'acc_per_category',
            'correct',
            'duration_per_category',
            'coreCompetencies',
            'detailedCompetencyLevels',
            'note'
        ));

        if ($feedback) {
            return $redirect->with('success', 'Thank you! Your feedback has been submitted successfully.');
        }

        return $redirect;
    
    }

}
