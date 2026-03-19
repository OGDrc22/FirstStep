<?php

namespace App\Services;

class RecommendationService
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

        return [
            'mode' => 'single',
            'recommended_track' => $predicted['track'],
            'averageAcc' => $attempt->accuracy_per_category,
            'averageDuration' => $attempt->duration_per_category,
        ];
    }

    private function handleMultiple($examResults)
    {
        $averageAcc = $this->calculateAverageAccuracy($examResults);
        $averageDuration = $this->calculateAverageDuration($examResults);
        $mlScores = $this->aggregateMLPredictions($examResults);

        $scorePerTrack = $this->computeScores($averageAcc, $averageDuration);

        // ✅ Combine ML + computed scores
        $finalScores = [];

        foreach ($scorePerTrack as $track => $score) {
            $ml = $mlScores[$track] ?? 0;

            $finalScores[$track] = (0.6 * $score) + (0.4 * $ml);
        }

        $recommendedTrack = collect($finalScores)
            ->sortDesc()
            ->keys()
            ->first();

        $trackPercentage = $this->computeTrackPercentage($examResults);

        return [
            'mode' => 'multiple',
            'recommended_track' => $recommendedTrack,
            'averageAcc' => $averageAcc,
            'averageDuration' => $averageDuration,
            'scorePerTrack' => $finalScores,
            'trackPercentage' => $trackPercentage
        ];
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

    private function computeTrackPercentage($examResult) {
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

    private function getTrackPercentage($examResult) {
        $trackPercentage = [];
        $count = count($examResult);

        foreach ($examResult as $attempt) {
            $trackPercentage[] = $attempt->track_percentage;
        }
        return $trackPercentage;
    }
}

// {
//     "Computer Engineering":
//         {
//             "track":"Computer Engineering",
//             "percentage":37.04
//         },
//     "Computer Science":
//         {
//             "track":"Computer Science",
//             "percentage":30.59
//         },
//     "Information Technology":
//         {
//             "track":"Information Technology",
//             "percentage":9.44
//         },
//     "Multimedia Arts":
//         {
//             "track":"Multimedia Arts",
//             "percentage":22.93
//         }
// }
// {
//     "Computer Engineering":
//         {
//             "track":"Computer Engineering",
//             "percentage":37.04
//         },
//     "Computer Science":
//         {
//             "track":"Computer Science",
//             "percentage":30.59
//         },
//     "Information Technology":
//         {
//             "track":"Information Technology",
//             "percentage":9.44
//         },
//     "Multimedia Arts":
//         {
//             "track":"Multimedia Arts",
//             "percentage":22.93
//         }
// }