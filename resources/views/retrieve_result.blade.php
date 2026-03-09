<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrieve Result</title>

    <link rel="stylesheet" href="{{ asset('assets/css/results.css') }}">
</head>
<body>
    

    @if ($errors->has('email'))
        <div class="alert alert-danger">{{ $errors->first('email') }}</div>
    @endif

    @if (!isset($examResult))
        <div class="login-container">
            <h2>Login</h2>
            <form method="POST" action="/get-result">
                @csrf
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" >
                </div>
                <button type="submit" class="" name="action" value="latest">Get Latest Result</button>
                <button type="submit" class="" name="action" value="all">Get All My Result</button>
            </form>
        </div>
    @endif

    @if (isset($examResult))
        @if ($action === 'latest')
            <div class="result-container-info">
                <div class="results-header">  
                    <h2>Exam Result for {{ $username }}</h2>
                    <p>Score: {{ $examResult->score }} / {{ count($questionsData) }}</p>
                    <p>Recommended Track: {{ $predictedTrack }}</p>
                </div>  

                <div class="pie-container">
                    <div class="chart-info">
                        <div class="info">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Track Percentage</th>
                                        <th>Accuracy</th>
                                        <th>Average Time (seconds)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div><div style="background-color: violet; width: 32px; height: 16px; border-radius: 4px;"></div>Computer Engineering</div>
                                        </td>
                                        <td>{{ $trackPercentage['Computer Engineering'] }}%</td>
                                        <td>{{ $acc_per_category['Computer Engineering'] * 100 }}%</td>
                                        <td>{{ $avg_time_per_category['Computer Engineering'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div><div style="background-color: yellow; width: 32px; height: 16px; border-radius: 4px;"></div>Computer Science</div>
                                        </td>
                                        <td>{{ $trackPercentage['Computer Science'] }}%</td>
                                        <td>{{ $acc_per_category['Computer Science'] * 100 }}%</td>
                                        <td>{{ $avg_time_per_category['Computer Science'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div><div style="background-color: blue; width: 32px; height: 16px; border-radius: 4px;"></div>Information Technology</div>
                                        </td>
                                        <td>{{ $trackPercentage['Information Technology'] }}%</td>
                                        <td>{{ $acc_per_category['Information Technology'] * 100 }}%</td>
                                        <td>{{ $avg_time_per_category['Information Technology'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div><div style="background-color: lightgreen; width: 32px; height: 16px; border-radius: 4px;"></div>Multimedia Arts</div>
                                        </td>
                                        <td>{{ $trackPercentage['Multimedia Arts'] }}%</td>
                                        <td>{{ $acc_per_category['Multimedia Arts'] * 100 }}%</td>
                                        <td>{{ $avg_time_per_category['Multimedia Arts'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="chart">
                        <canvas id="doughnutChart" width="400" height="400"></canvas>
                        <!-- <figure class="pie-chart" style="background:
                            conic-gradient(
                                from 0deg,
                                violet 0,
                                violet calc({{ $trackPercentage['Computer Engineering'] }}%),
                                yellow calc({{ $trackPercentage['Computer Engineering'] }}%),
                                yellow calc({{ $trackPercentage['Computer Engineering'] }}% + {{ $trackPercentage['Computer Science'] }}%),
                                blue calc({{ $trackPercentage['Computer Engineering'] }}% + {{ $trackPercentage['Computer Science'] }}%),
                                blue calc({{ $trackPercentage['Computer Engineering'] }}% + {{ $trackPercentage['Computer Science'] }}% + {{ $trackPercentage['Information Technology'] }}%),
                                lightgreen calc({{ $trackPercentage['Computer Engineering'] }}% + {{ $trackPercentage['Computer Science'] }}% + {{ $trackPercentage['Information Technology'] }}%),
                                lightgreen calc({{ $trackPercentage['Computer Engineering'] }}% + {{ $trackPercentage['Computer Science'] }}% + {{ $trackPercentage['Information Technology'] }}% + {{ $trackPercentage['Multimedia Arts'] }}%)

                            );">
                        </figure> -->
                    </div>
                </div>
            </div>

            <!-- <div class="progress-flex" style="background-color: gray">
                <div class="percent" style="width: {{ $trackPercentage['Computer Engineering'] }}%; background-color: violet;">
                    <h3>Computer Engineering: {{ $trackPercentage['Computer Engineering'] }}%</h3>
                </div>
                <div class="percent" style="width: {{ $trackPercentage['Computer Science'] }}%; background-color: yellow;">
                    <h3>Computer Science: {{ $trackPercentage['Computer Science'] }}%</h3>
                </div>
                <div class="percent" style="width: {{ $trackPercentage['Information Technology'] }}%; background-color: blue;">
                    <h3>Information Technology: {{ $trackPercentage['Information Technology'] }}%</h3>
                </div>
                <div class="percent" style="width: {{ $trackPercentage['Multimedia Arts'] }}%; background-color: lightgreen;">
                    <h3>Multimedia Arts: {{ $trackPercentage['Multimedia Arts'] }}%</h3>
                </div>
            </div> -->
        
            <h2>Question Review</h2>

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
                        <p class="bg-success-alpha correctAnswer">Correct Answer: {{ $questionsData[$index]['keyAns'][0] }}. {{ $questionsData[$index]['keyAns'][1] }}</p>
                        <p>Duration: {{ $questionsData[$index]['duration'] }}</p>
                    </div>
                @endforeach


        @elseif($action === 'all')
            <h2>All Exam Attempt Results for {{ $username }}</h2>
            <div class="all-result-tb">
                <table>
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th>Date</th>
                            <th>Score</th>
                            <th>Track Percentage</th>
                            <th>Predicted Track</th>
                            <!-- <th>Remarks</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($examResult as $exam)
                            <tr onclick="window.location='{{ route('show-exam-result', $exam->id) }}'" style="cursor: pointer">
                                <!-- <td>{{ $exam->id }}</td> -->
                                <td>{{ $exam->created_at->format('M d, Y: h:i') }}</td>
                                <td>{{ $exam->score }}</td>
                                <td>
                                    @php
                                        $sorted = collect($exam->track_percentage)->sortDesc();
                                    @endphp
                                    @foreach ($sorted as $track => $percentage)
                                        {{ $track }}: {{ $percentage }}% <br>
                                    @endforeach
                                </td>
                                <td>{{ $exam->predicted_track }}</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- @foreach ($averageAcc as $ac_p)
                <p>{{ $ac_p }}</p>
            @endforeach


            <h3>....</h3>
            <p>{{ $averageDuration['Information Technology'] }}</p> -->

            <h3>Recommended track based on all exam attempt:</h3>
            <h4>{{ $recommendedTrack }}</h4>

            <div class="line" style="width: 30vw; aspect-ratio: 5/1;">
                <canvas id="stackedLineChart"></canvas>
            </div>
        @endif
    @endif

    
    <a href="{{ route('welcome') }}">
        Home
    </a>

    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js"></script>


    @if (isset($examResult) && $action === 'all')
    <script>
        const ctx = document.getElementById('stackedLineChart').getContext('2d');

        Chart.defaults.font.size = 16;
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['IT', 'CPE', 'CS', 'MMA'],
                datasets: [
                    {
                        label: 'Average Accuracy (%)',
                        data: [
                            {{ $averageAcc['Information Technology'] }},
                            {{ $averageAcc['Computer Engineering'] }},
                            {{ $averageAcc['Computer Science'] }},
                            {{ $averageAcc['Multimedia Arts'] }}
                        ],
                        borderWidth: 2,
                        tension: 0.3,
                        yAxisID: 'yAccuracy'
                    },
                    {
                        label: 'Average Time Taken (sec)',
                        data: [
                            {{ $averageDuration['Information Technology'] }},
                            {{ $averageDuration['Computer Engineering'] }},
                            {{ $averageDuration['Computer Science'] }},
                            {{ $averageDuration['Multimedia Arts'] }}
                        ],
                        borderWidth: 2,
                        tension: 0.3,
                        yAxisID: 'yDuration'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Average Accuracy vs Average Time per Track'
                    }
                },
                scales: {
                    x: {
                        stacked: false
                    },
                    yAccuracy: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Average Accuracy (%)'
                        },
                        max: 100
                    },
                    yDuration: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Average Time (seconds)'
                        },
                        grid: {
                            drawOnChartArea: false // 👈 VERY important
                        }
                    }
                }
            }
        });
    </script>
    @elseif (isset($examResult) && $action === 'latest')
    <script>
        
        const ctx2 = document.getElementById('doughnutChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data:  {
                labels: [
                    'Red',
                    'Blue',
                    'Yellow'
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: [300, 50, 100],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    hoverOffset: 4
                }]
            },
             options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Track Percentage Distribution'
                    }
                }
             }
        });
    </script>
    @endif

</body>
</html>