<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;

class ExamResultService {
    public function validateStudent($student, $request) {
        if (!$student) {
            // Return JSON if called via AJAX
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You must be logged in to submit the exam.'], 401);
            }
            return back()->withErrors('You must be logged in to submit the exam.');
        }
    }

    public function getAbility_Interest($exam_data, $studentAnswer, $keyAns) {
        $rawAbility_Interest = [];
        foreach ($exam_data as $i => $q) {

            $userAnswer = $studentAnswer[$i] ?? null;
            $type = $q['type'];

            $category = $q['category'] ?? null;

            // dd($category, $allCategories);
            if ($category) {
                $rawAbility_Interest[$category]['correct'] = $rawAbility_Interest[$category]['correct'] ?? 0;
                $rawAbility_Interest[$category]['total'] = $rawAbility_Interest[$category]['total'] ?? 0;
                $rawAbility_Interest[$category]['interest'] = $rawAbility_Interest[$category]['interest'] ?? 0;
            }

            if ($type === "Objective" || $type === "Situational") {

                $keyAnswer = $keyAns[$i] ?? null;

                $exam_data[$i]['keyAnswer'] = $keyAnswer;

                if ($userAnswer && $keyAnswer) {

                    $rawAbility_Interest[$category]['total']++;

                    if ($userAnswer === $keyAnswer) {
                        $rawAbility_Interest[$category]['correct']++;
                        $exam_data[$i]['isCorrect'] = 'true';
                    } else {
                        $exam_data[$i]['isCorrect'] = 'false';
                    }

                } else {
                    $exam_data[$i]['isCorrect'] = 'false';
                }
            }

            elseif ($type === "Preference") {

                $choiceMap = $q['choice_equivalent'] ?? [];
                if ($userAnswer && isset($choiceMap[$userAnswer])) {

                    $mappedCategory = $choiceMap[$userAnswer];

                    $rawAbility_Interest[$mappedCategory]['interest'] =
                        ($rawAbility_Interest[$mappedCategory]['interest'] ?? 0) + 1;

                }

                // No correctness
                $exam_data[$i]['isCorrect'] = null;
            }
        }
        return [$rawAbility_Interest, $exam_data];
    }

    public function getAptitude($exam_data, $ability_and_interest) {
        $finalScores = [];

        $totalPreference = count(array_filter($exam_data, fn($q) => $q['type'] === "Preference"));

        foreach ($ability_and_interest as $cat => $data) {

            $correct = $data['correct'] ?? 0;
            $total = $data['total'] ?? 0;
            $interest = $data['interest'] ?? 0;

            $abilityScore = $total > 0 ? ($correct / $total) * 100 : 0;

            $interestScore = $totalPreference > 0
                ? ($interest / $totalPreference) * 100
                : 0;

            $finalScores[$cat] = ($abilityScore * 0.6) + ($interestScore * 0.4);
        }
        
        $aptitude = collect($finalScores)->max();
        return $aptitude;

    }

    public function calculateCoreCopetency($exam_data, $competencyOrder) {

        $competencyScores = [];

        foreach ($competencyOrder as $comp) {
            $competencyScores[$comp] = [
                'correct' => 0,
                'total' => 0,
                'time' => 0
            ];
        }


        foreach ($exam_data as $q) {
            
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

        return $competencyScores;
    }
    
    public function calculateCompetencyScore($competencyScores) {
        $features =[];
        foreach ($competencyScores as $i => $comp) {
            $correctA = $competencyScores[$i]['correct'];
            $tl = $competencyScores[$i]['total'];
            $features[$i] = $tl > 0 ? $correctA / $tl : 0;
        }
        return $features;
    }
    public function getCoreCompetency($normalizedConfidence, $competencyScores) {

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



        foreach ($coreCompetencies as $name => $score) {

            $allNotAssessed = true;

            foreach ($coreMap[$name] as $comp) {
                if ($competencyScores[$comp]['total'] > 0) {
                    $allNotAssessed = false;
                    break;
                }
            }

            $level = $allNotAssessed ? "Not Assessed" : $this->getCoreCompetencyLevels($score);

            $coreCompetencies[$name] = [
                'score' => $score,
                'level' => $level
            ];

        }
        return $coreCompetencies;
    }

    public function runMainAlgorithm($payload) {
        $scriptPath = base_path('public/assets/scripts/main_algo_new.py');
        $command = "python3 $scriptPath";

        $process = proc_open($command, [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ], $pipes);

        if (!is_resource($process)) {
            throw new \Exception('Failed to run exam evaluation script.');
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

        $resData = json_decode($result, true);

        if (!$resData || !isset($resData['predicted_track'])) {
            \Log::error('Invalid Python response', [
                'result' => $result,
                'error' => $error
            ]);

            throw new \Exception('Exam evaluation failed.');
        }

        return $resData;
    }

    public function getCognitiveLevel(float $accuracy, float $avg_time, float $baseline): string {
        $isFast = $avg_time < $baseline;
        if ($accuracy >= 0.8) {
            return $isFast ? "Analytical Thinker" : "Careful Thinker";
        }
        
        return $isFast ? "Guessing" : "Struggling";
    }
    
    public function getLevel($score)
    {
        if ($score >= 0.90) return "Highly Advance";
        if ($score >= 0.75) return "Proficient";
        if ($score >= 0.40) return "Developing";
        return "Low";
    }

    public function getCoreCompetencyLevels($score)
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
            'Algorithmic Thinking' => "You have a natural talent for breaking down complex problems into step-by-step solutions.",
            'Syntax & Structure Analysis' => "Your attention to detail and structural understanding are key to writing clean, maintainable code."
        ];

        // 4. Construct the Final Note
        $note = "Based on your overall score of " . number_format($topScore, 2) . "%, ";
        $note .= "we strongly recommend the **" . $topTrack . "** pathway due to " . $recommendations[$topTrack];
        $note .= " " . $strengthNotes[$strength];
        // dd($competencies);
        
        if ($competencies[$weakness] < 40) {
            $note .= " However, consider taking elective workshops in **" . $weakness . "** to round out your technical profile.";
        }
        if ($topTrack == 'Multimedia Arts' && $competencies['Syntax & Structure Analysis'] > 60) {
            $note .= " Your unique combination of logic and design makes you a prime candidate for **Front-end Engineering** or **Technical Art** in gaming.";
        }

        return $note;
    }

    public function saveToDB($student, $correct, $predictedTrack, $secondaryTrack, $trackPercentage, $coreCompetencies, $detailedCompetencyLevels, $note, $aptitude, $duration_per_category, $questions, $questionsData, $acc_per_category) {
        
        DB::transaction(function () use ($student, $correct, $predictedTrack, $secondaryTrack, $trackPercentage, $coreCompetencies, $detailedCompetencyLevels, $note, $aptitude, $duration_per_category, $questions, $questionsData, $acc_per_category) {
            $student->examResults()->create([
                'score' => $correct,
                'predicted_track' => $predictedTrack,
                'secondary_track' => $secondaryTrack, //
                'track_percentage' => $trackPercentage,
                'core_competencies' => $coreCompetencies, //
                'detailed_competencies' => $detailedCompetencyLevels, //
                'evaluation_note' => $note, //
                'aptitude' => $aptitude,
                'accuracy_per_category' => $acc_per_category,
                'duration_per_category' => $duration_per_category,
                'questionsData' => $questionsData,
                'questions' => $questions,
            ]);
        });
    }

    public function saveFeedback($student, $request, $feedback) {
        DB::transaction(function () use ($student, $request, $feedback) {
            $student->feedbacks()->create([
                'feedback_text' => $feedback, // MATCHES the Model and Database
                'job_id' => $request->job_id
            ]);
        });
    }
}