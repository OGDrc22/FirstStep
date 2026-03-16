<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamJob;
class ExamReasultController extends Controller
{

//     public function submitExam(Request $request)
// {
//     dd('SUBMIT EXAM HIT');
// }

    public function submitExam(Request $request)
    {
        // dd("hit");
        $studentAnswer = $request->input('answer');
        $keyAns = session('answer_keys');
        $keyAnsText = session('answer_keys_text');
        $exam_qd = json_decode($request->input('questionData'), true);

        // dd($studentAnswer, $keyAns, $exam_qd);
        // Get the student from session / "logged in" from Assessment Entry
        $student = Auth::guard('web')->user();

        if (!$student) {
            // Return JSON if called via AJAX
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You must be logged in to submit the exam.'], 401);
            }
            return back()->withErrors('You must be logged in to submit the exam.');
        }

        // dd($student);
        $job = ExamJob::findOrFail($request->job_id);
        $assessmentPayload = $job->payload;
        // dd($request->job_id, $assessmentPayload);


        $questions = [];
        
        foreach ($exam_qd as $i => $q) {
            $questions[$i] = $q['questionText'];
        }

        // dd($questions);


        foreach ($keyAns as $i => $ans) {
            $exam_qd[$i]['keyAnswer'] = $keyAns[$i];
            if (empty($studentAnswer[$i])) {
                $exam_qd[$i]['isCorrect'] = 'false';
            }
        }

        $totalQuestions = count($keyAns);
        $correct = 0;

        foreach ($studentAnswer as $i => $ans) {
            $exam_qd[$i]['keyAnswer'] = $keyAns[$i];
            if (isset($keyAns[$i]) && $ans === $keyAns[$i]) {
                $correct++;
                $exam_qd[$i]['isCorrect'] = 'true';
            } else if ($ans !== $keyAns[$i]) {
                $exam_qd[$i]['isCorrect'] = 'false';
            }
        }
        // dd($exam_qd, $studentAnswer, $keyAns, $correct);

        // dd($correct);



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

        $competencyScores = [];

        foreach ($competencyOrder as $comp) {
            $competencyScores[$comp] = [
                'correct' => 0,
                'total' => 0,
                'time' => 0
            ];
        }


        foreach ($exam_qd as $q) {
            
            $competencies = $q['competencies']; 
            $corr = $q['isCorrect'];
            $time = $q['duration'];

            foreach ($competencies as $comp) {
                
                if (!isset($competencyScores[$comp])) {
                    $competencyScores[$comp] = [
                        'correct' => 0,
                        'total' => 0,
                        'time' => 0
                    ];
                }

                $competencyScores[$comp]['total']++;
                $competencyScores[$comp]['time'] += $time;

                if ($corr === "true" || $corr === true) {
                    $competencyScores[$comp]['correct']++;
                }
            }
        }
        // dd($competencyScores);

        $features = [];

        // Correct/Total in competencyScores 
        foreach ($competencyScores as $i => $comp) {
            $correctA = $competencyScores[$i]['correct'];
            $tl = $competencyScores[$i]['total'];
            $features[$i] = $tl > 0 ? $correctA / $tl : 0;
        }

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

            $cognitive[$name] = $this->getCognitiveLevel($accuracy, $avgTime, $baseline);
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
            $normalizedConfidence[$name] = $value / $maxConfidence;
        }



        $coreMap = [
            "Logical-Mathematical Reasoning" => [
                "logical_reasoning",
                "algorithmic_thinking",
                "problem_solving"
            ],

            "Syntax & Structure Analysis" => [
                "syntax_analysis"
            ],

            "Systems Hardware & Networking" => [
                "hardware_systems",
                "networking_systems",
                "system_organization"
            ],

            "Digital Aesthetics & UI Design" => [
                "digital_creativity",
                "ui_design"
            ]
        ];



        $coreCompetencies = [];

        $coreCompetencies["Logical-Mathematical Reasoning"] =
            ($normalizedConfidence["logical_reasoning_confidence"]
            + $normalizedConfidence["algorithmic_thinking_confidence"]
            + $normalizedConfidence["problem_solving_confidence"]) / 3;

        $coreCompetencies["Syntax & Structure Analysis"] =
            $normalizedConfidence["syntax_analysis_confidence"];

        $coreCompetencies["Systems Hardware & Networking"] =
            ($normalizedConfidence["hardware_systems_confidence"]
            + $normalizedConfidence["networking_systems_confidence"]
            + $normalizedConfidence["system_organization_confidence"]) / 3;

        $coreCompetencies["Digital Aesthetics & UI Design"] =
            ($normalizedConfidence["digital_creativity_confidence"]
            + $normalizedConfidence["ui_design_confidence"]) / 2;

        
        $coreCompetenciesAsLevel = [];

        foreach ($coreCompetencies as $name => $score) {

            $allNotAssessed = true;

            foreach ($coreMap[$name] as $comp) {
                if ($competencyScores[$comp]['total'] > 0) {
                    $allNotAssessed = false;
                    break;
                }
            }

            if ($allNotAssessed) {
                $coreCompetenciesAsLevel[$name] = "Not Assessed";
            } else {
                $coreCompetenciesAsLevel[$name] = $this->getCoreCompetencyLevels($score);
            }

        }


        $detailedCompetencyLevels = [];

        foreach ($normalizedConfidence as $name => $score) {
            
            $comp = str_replace('_confidence', '', $name);
            if ($competencyScores[$comp]['total'] == 0) {
                $detailedCompetencyLevels[$comp] = "Not Assessed";
            } else {
                $detailedCompetencyLevels[$comp] = $this->getLevel($score);
            }
        }
        // dd($coreCompetencies, $coreCompetenciesAsLevel, $detailedCompetencyLevels);


        // Prepare payload for Python script
        $payload = json_encode([
            'assessment' => $assessmentPayload,
            'features' => $vector
        ]);


        // dd($payload);


        $command = "python assets/scripts/main_algo_new.py";
        // dd($command);
        $process = proc_open($command, [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ], $pipes);

        if (!is_resource($process)) {
            return back()->withErrors('Failed to run exam evaluation script.');
        }

        fwrite($pipes[0], $payload);
        fclose($pipes[0]);

        $result = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        proc_close($process);

        if ($error) {
            \Log::error('Python error', ['error' => $error]);
        }



        // output
        $resData = json_decode($result, true);
        if (!$resData || !isset($resData['predicted_track'])) {
            return back()->withErrors('Exam evaluation failed. Please try again.');
        }

        // dd($exam_qd);


        // dd($exam_qd, $keyAns);
        $predictedTrack = $resData['predicted_track'];
        $secondaryTrack = $resData['secondary_track'];
        $trackPercentage = $resData['track_percentage'];

        $accuracy = $resData['model_accuracy'];

        $note = $this->generateCounselorNote($trackPercentage, $coreCompetencies);

        dd($predictedTrack, $secondaryTrack, $trackPercentage, $coreCompetencies, $coreCompetenciesAsLevel, $note, $detailedCompetencyLevels, $resData);
        // dd($duration_per_category);
        // dd($accuracy);

        // dd($keyAns);
        // Save results
        // DB::transaction(function () use ($student, $correct, $predictedTrack, $trackPercentage, $accuracy, $duration_per_category, $questions, $questionsData, $acc_per_category) {
        //     $student->examResults()->create([
        //         'score' => $correct,
        //         'predicted_track' => $predictedTrack,
        //         'track_percentage' => $trackPercentage,
        //         'accuracy' => $accuracy,
        //         'accuracy_per_category' => $acc_per_category,
        //         'duration_per_category' => $duration_per_category,
        //         'questionsData' => $questionsData,
        //         'questions' => $questions,
        //     ]);
        // });

        // dd($acc_per_category);
        $accuracy = $resData['sys_accuracy'] * 100 . "%";


        // FEEDBACK DATA

        $feedback = null;
        

        if ($request->input('feedback-input')) {
            $feedback = $request->input('feedback-input');
            // dd($feedback);

            DB::transaction(function () use ($student, $request, $feedback) {
                $student->feedbacks()->create([
                    'feedback_text' => $feedback, // MATCHES the Model and Database
                    'job_id' => $request->job_id
                ]);
            });
        }

        $redirect = view('exam_result', compact(
            'resData',
            'questions',
            'questionsData',
            'keyAns',
            'keyAnsText',
            'predictedTrack',
            'trackPercentage',
            'accuracy',
            'acc_per_category',
            'correct',
            'totalQuestions',
            'duration_per_category',
            'coreCompetencies',
            'coreCompetenciesAsLevel',
            'detailedCompetencyLevel'
        ));

        if ($feedback) {
            return $redirect->with('success', 'Thank you! Your feedback has been submitted successfully.');
        }

        return $redirect;
    
    }

    private function getCognitiveLevel(float $accuracy, float $avg_time, float $baseline): string {
        $isFast = $avg_time < $baseline;
        if ($accuracy >= 0.8) {
            return $isFast ? "Analytical Thinker" : "Careful Thinker";
        }
        
        return $isFast ? "Guessing" : "Struggling";
    }
    
    private function getLevel($score)
    {
        if ($score >= 0.90) return "Highly Advance";
        if ($score >= 0.75) return "Proficient";
        if ($score >= 0.40) return "Developing";
        return "Low";
    }

    private function getCoreCompetencyLevels($score)
    {
        if ($score >= 0.75) return "Highly Advance";
        if ($score >= 0.40) return "Proficient";
        return "Low";
    }

    public function generateCounselorNote($scores, $competencies) {
        // 1. Identify Top Track
        arsort($scores); // Sorts high to low
        $topTrack = array_key_first($scores);
        $topScore = reset($scores);

        // 2. Identify Top Strength & Weakness
        arsort($competencies);
        $strength = array_key_first($competencies);
        $weakness = array_key_last($competencies);

        // 3. Logic Mapping
        $recommendations = [
            'Computer Science' => "your high aptitude for abstract logic and algorithmic structures. You are well-suited for roles in software engineering and data science.",
            'Information Technology' => "your strength in systems integration and infrastructure. You would excel in network administration or cybersecurity.",
            'Computer Engineering' => "your balance of hardware understanding and low-level programming. You should look into embedded systems or robotics.",
            'Multimedia Arts' => "your exceptional creative vision and ability to merge technology with visual storytelling. You are well-positioned for careers in UI/UX design, game development, and digital media production.",
        ];

        $strengthNotes = [
            'Logical-Mathematical Reasoning' => "Your strong logical foundation will make complex coding much easier for you.",
            'Digital Aesthetics & UI Design' => "Your creative eye gives you a significant advantage in Front-End development.",
            'Systems Hardware & Networking' => "Your hands-on technical skills are a perfect fit for systems architecture.",
            'Algorithmic Thinking' => "You have a natural talent for breaking down complex problems into step-by-step solutions."
        ];

        // 4. Construct the Final Note
        $note = "Based on your overall score of " . number_format($topScore, 2) . "%, ";
        $note .= "we strongly recommend the **" . $topTrack . "** pathway due to " . $recommendations[$topTrack];
        $note .= " " . $strengthNotes[$strength];
        
        if ($competencies[$weakness] < 40) {
            $note .= " However, consider taking elective workshops in **" . $weakness . "** to round out your technical profile.";
        }
        if ($topTrack == 'Multimedia Arts' && $competencies['Algorithmic Thinking'] > 60) {
            $note .= " Your unique combination of logic and design makes you a prime candidate for **Front-end Engineering** or **Technical Art** in gaming.";
        }

        return $note;
    }
}
