<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>

    <link rel="stylesheet" href="{{ asset('assets/css/results.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flash_message.css') }}">
</head>


<body class="bg-overlay">
    @if (isset($success))
        <div class="alert heads-up-message hum-success">
            <p class="p p-success">Your feedback has been submitted successfully!</p>
        </div>
    @endif

    <div class="content">
        @if (isset($resData))
            <h3 class="result-title">Assessment Result</h3>

            <div class="dashboard-grid">
                        <div class="user-card">
                            <div class="user-detail">
                                <i class="icon-user" alt="User Icon"></i>
                                <span class="user-name">{{ $username }}</span>
                            </div>
                            <div class="user-detail">
                                <i class="icon-email" alt="Email Icon"></i>
                                <span class="user-email">{{ $useremail }}</span>
                            </div>
                        </div>
                        <div class="result-display-card card">

                            <div class="card-data-row">
                                <span class="card-label">Primary Recommendation</span>
                                <span class="card-value highlight">{{ $predictedTrack['track'] }}</span>
                                <p>{{ $note }}</p>
                            </div>

                            <div class="card-data-row">
                                <span class="card-label">Seondary Recommendation</span>
                                <span class="card-value highlight">{{ $secondaryTrack['track'] }}</span>
                            </div>
                        </div>
                        <div class="card aptitude-card">
                            <h1>{{ $aptitude }}%</h1>
                            <h3>APTITUDE SCORE</h3>
                        </div>

                        <div class="left-chart card">
                            <div class="chart-label">
                                <h3>Core Competencies</h3>
                                <i class="icon-chart" alt="Chart Icon"></i>
                            </div>

                            <div class="chart-bar" style="position: relative;">
                                <!-- <div style="display: flex; position: relative; height: 250px;"> -->

                                    <!-- LEFT: Labels -->
                                    <div id="custom-labels" style="position: absolute;
                                        top: 0;
                                        left: 0;
                                        width: 100%;
                                        pointer-events: none;
                                        display: flex;
                                        flex-direction: column;
                                        height: 100%;
                                        ">
                                    </div>

                                    <!-- RIGHT: Chart -->
                                    <div style="height: 100%; width: 100%;">
                                        <canvas id="bar-chart"></canvas>
                                    </div>

                                <!-- </div> -->
                            </div>
                        </div>
                        
                        <div class="right-chart card">
                            <div class="chart-label">
                                <h3>Track Breakdown</h3>
                                <i class="icon-pie" alt="Pie Chart Icon"></i>
                            </div>

                            <div class="chart">
                                <div class="chart-wrapper">
                                    <canvas id="doughnutChart">
                                    </canvas>
                                </div>
                                <div class="chart-info">
                                    <table>
                                        <thead>
                                            <tr>
                                                <!-- <th>Track</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div
                                                            style="background-color: #8b5cf6; width: 8px; height: 8px; border-radius: 50%;">
                                                        </div>
                                                        Computer Engineering
                                                    </div>
                                                </td>

                                                <td>
                                                    <div>
                                                        <div
                                                            style="background-color: #ffcd56; width: 8px; height: 8px; border-radius: 50%;">
                                                        </div>
                                                        Computer Science
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div
                                                            style="background-color: #36a2eb; width: 8px; height: 8px; border-radius: 50%;">
                                                        </div>
                                                        Information Technolgy
                                                    </div>
                                                </td>

                                                <td>
                                                    <div>
                                                        <div
                                                            style="background-color: #7dff7d; width: 8px; height: 8px; border-radius: 50%;">
                                                        </div>
                                                        Miltimedia Arts
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

            
            <div class="questions-review">
                
                <!-- Can be used as Answer Review -->
                @foreach ($questions as $index => $q)
                        <div class="question-review-card">
                            <h4 class="question-review">{{ $q }}</h4>

                            @php
                                $studentAns = $questionsData[$index]['answer'][0] ?? null;
                                $correctAns = $questionsData[$index]['keyAns'][0] ?? null;
                                $isCorrect = $studentAns === $correctAns;

                                $fullStudentAns = $questionsData[$index]['answer'][0] . ". " . $questionsData[$index]['answer'][1];
                                $fullCorrectAns = $questionsData[$index]['keyAns'][0] . ". " . $questionsData[$index]['keyAns'][1];
                            @endphp
                            <p class="{{ $isCorrect ? 'bg-correct-alpha' : 'bg-danger-alpha' }} stdntAnswer">
                                Your Answer:
                                @if (isset($questionsData[$index]['answer']))
                                    {{ $fullStudentAns }}
                                @else
                                    No Answer
                                @endif
                            </p>
                            <p class="bg-success-alpha correctAnswer">
                                Correct Answer:
                                @if (!empty($questionsData[$index]['keyAns'][0]))
                                    {{ $fullCorrectAns }}
                                @else
                                    Preference type of question (No Correct Answer)
                                @endif
                            </p>
                            <p>Duration: {{ $questionsData[$index]['duration'] }}</p>
                        </div>
                    @endforeach

            </div>
                <!-- <h3>System Accuracy: {{ $model_accuracy }}</h3> -->
        @endif
    </div>


        <a href="{{ route('welcome') }}">Home</a>

        <script src="{{ asset('assets/js/flash_message.js') }}"></script>
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
                    barThickness: 16,    // Height of the bar in pixels
                    maxBarThickness: 40, // Ensures it never gets too chunky'
                    // categoryPercentage: 0.8,   // more vertical spacing
                    // barPercentage: 0.9,        // keep bars solid
                    fill: false,
                    backgroundColor: [
                        'rgb(102, 130, 255)'
                    ],
                    borderWidth: 0,
                    borderRadius: 15,
                    borderSkipped: false,
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                plugins: [ChartDataLabels],
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    // layout: {
                    //     padding: { left: 50, right: 50 }
                    // },
                    plugins: {
                        legend: {
                            display: false
                        },
                        // datalabels: {
                        //     anchor: 'end',
                        //     align: 'end',
                        //     offset: 8,
                        //     color: '#666', // Matches the gray in your image
                        //     formatter: (value) => `${value.toFixed(0)}%`,
                        //     font: { weight: 'bold' }
                        // },
                        datalabels: { display: false },
                        // Tooltip
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    // Main Bar Label
                                    return `Total Score: ${context.parsed.x.toFixed(2)}%`;
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
                                            const score = (detailedCompetencies[key].score * 100).toFixed(2);
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
                            ticks: {
                                display: false
                            },
                            grid: {
                                display: false,   // Hides horizontal grid lines
                                drawBorder: false // Hides the Y-axis line
                            },
                            border: {
                                display: false
                            }
                        },
                        x: {
                            max: 100,
                            display: false,
                            grid: { 
                                display: false,
                                drawBorder: false
                            },
                        }
                    }
                }
            };
            new Chart(ctx_bar, config);

            const labelsContainer = document.getElementById('custom-labels');

            labelsContainer.innerHTML = labels.map((label, index) => {
                const level = levels[index];
                const percentage = scores[index].toFixed(2) + '%';

                return `
                    <div style="
                        display: flex;
                        justify-content: space-between;
                        flex: 1;
                        color: #666;
                        padding: 0 0 0 10px;
                    ">
                        <span>${label} — ${level}</span>
                        <span style="color:#666;">${percentage}</span>
                    </div>
                `;
            }).join('');

        </script>

</body>

</html>