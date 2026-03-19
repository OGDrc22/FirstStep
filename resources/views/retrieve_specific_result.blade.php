<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="{{ asset('assets/css/results.css') }}">
</head>

<body>
    <div class="result-display-card">
                <h3 class="card-user-title">Exam result for {{ $username }}</h3>

                <div class="card-data-row">
                    <span class="card-label">Score:</span>
                    <span class="card-value">{{ $examResult->score }}</span>
                </div>

                <div class="card-data-row">
                    <span class="card-label">Recommended Track:</span>
                    <span class="card-value highlight" style="color: #2D79C1;">{{ $predictedTrack['track'] }}</span>
                    <span class="card-value highlight" style="color: #2D79C1;">{{ $secondaryTrack['track'] }}</span>
                </div>
            </div>

            <div class="charts-container">
                <div class="left-chart">
                    <h3>Core Competecies</h3>

                    <div class="chart-bar">
                        <canvas id="bar-chart"></canvas>
                    </div>
                </div>

                <div class="right-chart">
                    <h3>Track Percentage:</h3>

                    <div class="chart">
                        <div class="chart-wrapper">
                            <canvas id="doughnutChart">
                            </canvas>
                        </div>
                        <div class="chart-info">
                            <div class="info">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Track</th>
                                            <th>Track Percentage</th>
                                            <th>Accuracy</th>
                                            <th>Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div>
                                                    <div
                                                        style="background-color: #640082; width: 32px; height: 16px; border-radius: 4px;">
                                                    </div>CE
                                                </div>
                                            </td>
                                            <td>{{ $trackPercentage['Computer Engineering']['percentage'] }}%</td>
                                            <td>{{ $acc_per_category['Computer Engineering'] * 100 }}%</td>
                                            <td>{{ $duration_per_category['Computer Engineering']}} s</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div>
                                                    <div
                                                        style="background-color: #ffcd56; width: 32px; height: 16px; border-radius: 4px;">
                                                    </div>CS
                                                </div>
                                            </td>
                                            <td>{{ $trackPercentage['Computer Science']['percentage'] }}%</td>
                                            <td>{{ $acc_per_category['Computer Science'] * 100 }}%</td>
                                            <td>{{ $duration_per_category['Computer Science']}} s</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div>
                                                    <div
                                                        style="background-color: #36a2eb; width: 32px; height: 16px; border-radius: 4px;">
                                                    </div>IT
                                                </div>
                                            </td>
                                            <td>{{ $trackPercentage['Information Technology']['percentage'] }}%</td>
                                            <td>{{ $acc_per_category['Information Technology'] * 100 }}%</td>
                                            <td>{{ $duration_per_category['Information Technology']}} s</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div>
                                                    <div
                                                        style="background-color: #7dff7d; width: 32px; height: 16px; border-radius: 4px;">
                                                    </div>MMA
                                                </div>
                                            </td>
                                            <td>{{ $trackPercentage['Multimedia Arts']['percentage'] }}%</td>
                                            <td>{{ $acc_per_category['Multimedia Arts'] * 100 }}%</td>
                                            <td>{{ $duration_per_category['Multimedia Arts']}} s</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h4>
                {{ $note }}
            </h4>

            <div class="questions-review">
                @foreach ($questions as $index => $q)
                    <div class="question-review-card">
                        <h4 class="question-review">{{ $q }}</h4>

                        @php
                            $studentAns = $questionsData[$index]['answer'][0] ?? null;
                            $correctAns = $questionsData[$index]['keyAns'][0] ?? null;
                            $isCorrect = $studentAns === $correctAns;
                        @endphp
                        <p class="{{ $isCorrect ? 'bg-correct-alpha' : 'bg-danger-alpha' }} stdntAnswer">
                            Your Answer:
                            @if (isset($questionsData[$index]['answer']))
                                {{  $questionsData[$index]['answer'][0]  }}. {{ $questionsData[$index]['answer'][1] }}
                            @else
                                No Answer
                            @endif
                        </p>
                        <p class="bg-success-alpha correctAnswer">Correct Answer: {{ $questionsData[$index]['keyAns'][0] }}.
                            {{ $questionsData[$index]['keyAns'][1] }}
                        </p>
                        <p>Duration: {{ $questionsData[$index]['duration'] }}</p>
                    </div>
                @endforeach

            </div>
    
    <a href="{{ url('/') }}">Home</a>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>

    <script>

        const ctx2 = document.getElementById('doughnutChart').getContext('2d');
            Chart.defaults.font.size = 14;
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: [
                        'Computer Engineering',
                        'Computer Science',
                        'Information Technology',
                        'Multimedia Atrs'
                    ],
                    datasets: [{
                        // label: 'Track Percentage',
                        data: [
                        {{ $trackPercentage['Computer Engineering']['percentage'] }},
                        {{ $trackPercentage['Computer Science']['percentage'] }},
                        {{ $trackPercentage['Information Technology']['percentage'] }},
                        {{ $trackPercentage['Multimedia Arts']['percentage'] }}
                        ],
                        backgroundColor: [
                            '#640082',
                            '#ffcd56',
                            '#36a2eb',
                            '#7dff7d'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        datalabels: {
                            display: false
                        },
                        title: {
                            display: false,
                            text: 'Track Percentage'
                        }
                    }
                }
            });



            // Convert the PHP array to a JS object
            const detailedCompetencies = @json($detailedCompetencyLevels);
            const filteredComp = Object.entries(detailedCompetencies)
                .filter(([key, item]) => item.score > 0);
            const coreCompetencies = @json($coreCompetencies);
            const filteredEntries = Object.entries(coreCompetencies)
                .filter(([key, item]) => item.score > 0);

            // 2. Map the filtered data
            const labels = filteredEntries.map(([key, item]) => key);
            const scores = filteredEntries.map(([key, item]) => item.score * 100);
            const levels = filteredEntries.map(([key, item]) => item.level);

            const compName = filteredComp.map(([key, item]) => key);
            const compScores = filteredComp.map(([key, item]) => item.score * 100);

            const ctx_bar = document.getElementById('bar-chart').getContext('2d');

            const data = {
                labels: labels,
                datasets: [{
                    axis: 'y',
                    label: labels,
                    data: scores,
                    barThickness: 30,    // Height of the bar in pixels
                    maxBarThickness: 40, // Ensures it never gets too chunky
                    fill: false,
                    backgroundColor: [
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 200, 102, 0.5)',
                        'rgba(120, 255, 102, 0.5)',
                        'rgba(102, 232, 255, 0.5)',
                    ],
                    borderColor: [
                        'rgb(153, 102, 255)',
                        'rgb(255, 200, 102)',
                        'rgb(120, 255, 102)',
                        'rgb(102, 232, 255)',
                    ],
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                plugins: [ChartDataLabels],
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    layout: {
                        padding: { left: 50, right: 200 }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        datalabels: {
                            anchor: 'end', // Position relative to the end of the bar
                            align: 'end',  // Position the label after the anchor point
                            offset: 4,
                            formatter: (value, context) => {
                                const level = levels[context.dataIndex];
                                const score = value.toFixed(1);
                                return `${level} (${score}%)`;
                            }
                        },
                        // Tooltip
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    // Main Bar Label
                                    return `Total Score: ${context.parsed.x.toFixed(1)}%`;
                                },
                                afterLabel: function(context) {
                                    const label = context.label;
                                    let lines = [];

                                    // 1. Define which sub-keys belong to which bar
                                    const mapping = {
                                        'Logical-Mathematical Reasoning': ['logical_reasoning', 'algorithmic_thinking', 'problem_solving'],
                                        'Syntax & Structure Analysis': ['syntax_analysis'],
                                        'Systems Hardware & Networking': ['hardware_systems', 'networking_systems', 'system_organization'],
                                        'Digital Aesthetics & UI Design': ['ui_design', 'digital_creativity']
                                    };

                                    // 2. Get the sub-keys for the current hovered bar
                                    const subKeys = mapping[label] || [];

                                    // 3. Loop through sub-keys and pull data from your 'detailedCompetencies' object
                                    subKeys.forEach(key => {
                                        if (detailedCompetencies[key]) {
                                            const score = (detailedCompetencies[key].score * 100).toFixed(1);
                                            const name = key.replace(/_/g, ' ').replace(/^\w/, c => c.toUpperCase());
                                            lines.push(`${name}: ${score}%`);
                                        }
                                    });

                                    return lines; // This returns each sub-item on a new line
                                }
                            }
                        }
                    },
                    scales: {
                        // 2. Hide the Labels on the left (Y-axis)
                        y: {
                            display: true,
                            grid: {
                                display: false // Optional: hides the horizontal grid lines
                            }
                        },
                        x: {
                            max: 100
                        }
                    }
                }
            };
            new Chart(ctx_bar, config);
    </script>

</body>

</html>
