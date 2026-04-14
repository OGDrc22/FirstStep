<?php

namespace App\Services;

class RetrieveResultService
{
    public function analyzeAllAttempts($examResults)
    {
        $attemptCount = $examResults->count();

        // ✅ SINGLE ATTEMPT
        if ($attemptCount === 1) {
            return $this->handleSingle($examResults->first());
        }

        // ✅ MULTIPLE ATTEMPTS
        return $this->handleMultiple($examResults);
    }

    private function handleSingle($attempt)
    {
        $predicted = $attempt->predicted_track;
        $secondRecommendation = $attempt->secondary_track;

        $averageAcc = $this->calculateAverageAccuracySingle($attempt);
        $averageAccuracy = [];
        foreach ($averageAcc as $name => $a) {
            if ($name != "Preference" && !empty($name)) {
                $averageAccuracy[$name] = $a;
            }
        }

        $averageDuration = $this->calculateAverageDurationSingle($attempt);

        $finalScores = $this->computeScores($averageAccuracy, $averageDuration);
        
        $rawTrackPercentage = $attempt->track_percentage;
        $rawScores = [];
        foreach ($rawTrackPercentage as $name => $p) {
            $rawScores[$name] = $p['percentage'];
        }
        

        $note = $this->generateCounselorNote($rawScores);

        return [
            'mode' => 'single',
            'recommended_track' => $predicted['track'],
            'second_recommendation' => $secondRecommendation['track'],
            'averageAcc' => $attempt->accuracy_per_category,
            'averageDuration' => $attempt->duration_per_category,
            'rawTrackPercentage' => $rawScores,
            'computedTrackPercentage' => $rawScores,
            'note' => $note
        ];
    }

    private function handleMultiple($examResults)
    {
        $averageAcc = $this->calculateAverageAccuracy($examResults);
        $averageAccuracy = [];
        foreach ($averageAcc as $name => $a) {
            if ($name != "Preference" && !empty($name)) {
                $averageAccuracy[$name] = $a;
            }
        }
        $averageDuration = $this->calculateAverageDuration($examResults);
        $mlScores = $this->aggregateMLPredictions($examResults);

        $scorePerTrack = $this->computeScores($averageAccuracy, $averageDuration);

        // ✅ Combine ML + computed scores
        $finalScores = [];

        $mlScores1 = [];

        foreach ($scorePerTrack as $track => $score) {
            $mlScores1[$track] = $mlScores[$track] ?? 0;
            $ml = $mlScores[$track] ?? 0;

            $finalScores[$track] = (0.6 * $score) + (0.4 * $ml);
        }

        // dd($mlScores1, $scorePerTrack, $finalScores);

        $recommendedTrack = collect($finalScores)
            ->sortDesc()
            ->keys()
            ->get(0);

        $secondRecommendation =  collect($finalScores)
            ->sortDesc()
            ->keys()
            ->get(1);


        $rawTrackPercentage = $this->computeRawTrackPercentage($examResults);
        // $rawScores = [];
        // foreach ($rawTrackPercentage as $name => $p) {
        //     $rawScores[$name] = $p['percentage'];
        // }

        // dd($rawTrackPercentage, $rawScores, $finalScores);
        $note = $this->generateCounselorNote($finalScores);

        return [
            'mode' => 'multiple',
            'recommended_track' => $recommendedTrack,
            'second_recommendation' => $secondRecommendation,
            'averageAcc' => $averageAccuracy,
            'averageDuration' => $averageDuration,
            'rawTrackPercentage' => $rawTrackPercentage,
            'computedTrackPercentage' => $finalScores,
            'note' => $note
        ];
    }
    private function computeRawTrackPercentage($examResult) {
            $trackPercentage = [];
            $count = count($examResult);

            foreach ($examResult as $attempt) {
                $attmp_tp = $attempt->track_percentage;
                foreach ($attmp_tp as $name => $ex) {
                    if (!isset($trackPercentage[$name])) {
                        $trackPercentage[$name] = [
                            'track' => $name,
                            'percentage' => 0
                        ];
                    }
                    $trackPercentage[$name]['percentage'] += $ex['percentage'];
                }
            }

            foreach ($trackPercentage as $name => $data) {
                $trackPercentage[$name]['percentage'] = round($data['percentage'] / $count, 2);
            }
            return $trackPercentage;
        }
    private function calculateAverageAccuracy($examResults)
    {
        $totals = [];
        $counts = [];

        foreach ($examResults as $attempt) {
            foreach ($attempt->accuracy_per_category as $track => $accuracy) {
                $totals[$track] = ($totals[$track] ?? 0) + $accuracy;
                $counts[$track] = ($counts[$track] ?? 0) + 1;
            }
        }

        $average = [];
        foreach ($totals as $track => $total) {
            $average[$track] = round(($total / $counts[$track]) * 100, 2);
        }

        return $average;
    }

    private function calculateAverageDuration($examResults)
    {
        $totals = [];
        $count = 0;

        foreach ($examResults as $attempt) {
            if (!empty($attempt->duration_per_category)) {
                $count++;

                foreach ($attempt->duration_per_category as $track => $duration) {
                    $totals[$track] = ($totals[$track] ?? 0) + $duration;
                }
            }
        }

        $average = [];

        if ($count > 0) {
            foreach ($totals as $track => $total) {
                $average[$track] = round($total / $count, 2);
            }
        }

        return $average;
    }

    private function calculateAverageAccuracySingle($attempt)
    {
        $totals = [];
        $counts = [];

        foreach ($attempt->accuracy_per_category as $track => $accuracy) {
            $totals[$track] = ($totals[$track] ?? 0) + $accuracy;
            $counts[$track] = ($counts[$track] ?? 0) + 1;
        }

        $average = [];
        foreach ($totals as $track => $total) {
            $average[$track] = round(($total / $counts[$track]) * 100, 2);
        }

        return $average;
    }

    private function calculateAverageDurationSingle($attempt)
    {
        $totals = [];
        $count = 0;

        if (!empty($attempt->duration_per_category)) {
            $count++;

            foreach ($attempt->duration_per_category as $track => $duration) {
                $totals[$track] = ($totals[$track] ?? 0) + $duration;
            }
        }

        $average = [];

        if ($count > 0) {
            foreach ($totals as $track => $total) {
                $average[$track] = round($total / $count, 2);
            }
        }

        return $average;
    }

    private function computeScores($averageAcc, $averageDuration)
    {
        $tracks = [
            'Information Technology',
            'Computer Engineering',
            'Computer Science',
            'Multimedia Arts'
        ];

        $scores = [];

        foreach ($tracks as $track) {
            $accuracy = $averageAcc[$track] ?? 0;
            $time = $averageDuration[$track] ?? 1;

            // ✅ improved time scoring
            $timeScore = 100 / ($time + 1);

            $scores[$track] =
                (0.7 * $accuracy) +
                (0.3 * $timeScore);
        }

        return $scores;
    }

    private function aggregateMLPredictions($examResults)
    {
        $scores = [];

        foreach ($examResults as $attempt) {
            // dd($attempt->predicted_track);
            $predicted = $attempt->predicted_track;

            if ($predicted) {
                $track = $predicted['track'];
                $percentage = $predicted['percentage'];

                $scores[$track] = ($scores[$track] ?? 0) + $percentage;
            }
        }

        return $scores;
    }

    private function generateCounselorNote($scores) {
        arsort($scores); // Sorts high to low
        $topTrack = array_key_first($scores);
        $topScore = reset($scores);

        // dd($scores, $topScore, $topTrack);

        $recommendations = [
            'Computer Science' => "your strong aptitude for abstract reasoning and problem-solving, which are essential for software development and data-driven fields.",
            'Information Technology' => "your ability to understand and manage systems, making you well-suited for roles in networking, cybersecurity, and IT support.",
            'Computer Engineering' => "your combined strengths in hardware and software, opening opportunities in embedded systems, robotics, and system design.",
            'Multimedia Arts' => "your creativity and ability to blend technology with visual storytelling, ideal for careers in UI/UX design, game development, and digital media.",
        ];

        $note = "Based on your overall performance, with a top score of " . number_format($topScore, 2) . "%, ";
        $note .= "the " . $topTrack . " pathway is highly recommended. ";
        $note .= "This is primarily due to " . $recommendations[$topTrack];

        return $note;
    }

    
}