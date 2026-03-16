<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrieve Result</title>
    <link rel="stylesheet" href="{{ asset('assets/css/results.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flash_message.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nav_bar.css') }}">
</head>
<body class="bg-overlay">

<header class="header-pcu">
        <div class="header-left">
            <img class="pcu-logo" src="{{ asset('assets/images/main_logo.png') }}" alt="PCU Logo">
            <span class="pcu-text-small">Philippine Christian University</span>
        </div>
        <div class="header-right">
            <span class="coi-text">COI First Step</span>
            <img class="pcu-coi-logo" src="{{ asset('assets/images/College_of_Informatics_72_R.png') }}" alt="COI Logo">
        </div>
    </header>

 @if ($errors->has('email'))
        <div class="alert alert-danger">{{ $errors->first('email') }}</div>
    @endif


    @if (!isset($examResult))
    <!--entering -->
        <div class="page-Cencontainer">
            <div class="login-card-wrapper">
                <div class="login-card">
                    <h2 class="form-title">Enter your credentials</h2>
                    
                    <form method="POST" action="/get-result">
                        @csrf
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Email@gmail.com" required>
                        </div>

                        <div class="btn-group">
                            <button type="submit" name="action" value="latest" class="btn-result">
                                <i class="btn-icon icon-search-status" alt="Search Icon"></i>
                                <span>Get Latest Result</span>
                            </button>
                            
                            <button type="submit" name="action" value="all" class="btn-result">
                                <i class="btn-icon icon-search-status" alt="Search Icon"></i>
                                <span>See All Result</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="home-btn-container">
                <a href="{{ route('welcome') }}" class="home-link"><i class="icon-home"></i> Back to Home</a>
            </div>
        </div>
    @endif


    
    <div class="dashboard-row">

    <!-- score board> --> 

        @if (isset($examResult))
            @if ($action === 'latest')
                <div class="result-display-card">
                    <h3 class="card-user-title">Exam result for {{ $username }}</h3>
                    
                    <div class="card-data-row">
                        <span class="card-label">Score:</span>
                        <span class="card-value">{{ $examResult->score }}</span>
                    </div>
                    
                    <div class="card-data-row">
                        <span class="card-label">Recommended Track:</span>
                        <span class="card-value highlight" style="color: #2D79C1;">{{ $predictedTrack }}</span>
                    </div>
                </div>


    <!-- percentage container> --> 

                    <div class="track-stats-card">
                        <div class="stats-table-wrapper">
                            <h3 class="card-title">Track Percentage Distribution</h3>
                            <table class="custom-stats-table">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Track %</th>
                                        <th>Accuracy</th>
                                        <th>Avg Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="dot violet"></span> Computer Engineering</td>
                                        <td>{{ $trackPercentage['Computer Engineering'] }}%</td>
                                        <td>{{ $acc_per_category['Computer Engineering'] * 100 }}%</td>
                                        <td>{{ $avg_time_per_category['Computer Engineering'] }}s</td>
                                    </tr>
                                    <tr>
                                        <td><span class="dot yellow"></span> Computer Science</td>
                                        <td>{{ $trackPercentage['Computer Science'] }}%</td>
                                        <td>{{ $acc_per_category['Computer Science'] * 100 }}%</td>
                                        <td>{{ $avg_time_per_category['Computer Science'] }}s</td>
                                    </tr>
                                    <tr>
                                        <td><span class="dot blue"></span> Information Technology</td>
                                        <td>{{ $trackPercentage['Information Technology'] }}%</td>
                                        <td>{{ $acc_per_category['Information Technology'] * 100 }}%</td>
                                        <td>{{ $avg_time_per_category['Information Technology'] }}s</td>
                                    </tr>
                                    <tr>
                                        <td><span class="dot green"></span> Multimedia Arts</td>
                                        <td>{{ $trackPercentage['Multimedia Arts'] }}%</td>
                                        <td>{{ $acc_per_category['Multimedia Arts'] * 100 }}%</td>
                                        <td>{{ $avg_time_per_category['Multimedia Arts'] }}s</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="chart-wrapper">
                                <canvas id="doughnutChart"></canvas>
                        </div>
                    </div>
        </div>
    </div>



   <!-- student score> --> 
        
    <div class="results-card-container">
        <h2 class="question-review-title">Question Review</h2>
        <div class="results-sub-card"> 

            @foreach ($questions as $index => $q)
                <div class="question-item">
                    <h4 class="question-text">{{ $q }}</h4>
                    
                    @php
                        $studentAns = $questionsData[$index]['answer'][0] ?? null;
                        $correctAns = $questionsData[$index]['keyAns'][0] ?? null;
                        $isCorrect = $studentAns === $correctAns;
                    @endphp
                    
                    <div class="answer-box">
                        <p class="{{ $isCorrect ? 'bg-correct-a' : 'bg-danger-a' }} answer-line">
                            <strong>Your Answer:</strong> 
                            @if (isset($questionsData[$index]['answer'][0]))
                                {{ $questionsData[$index]['answer'][0] }}. {{ $questionsData[$index]['answer'][1] }}
                            @else
                                No Answer
                            @endif
                        </p>
                        
                        <p class="bg-success-a answer-line">
                            <strong>Correct Answer:</strong> {{ $questionsData[$index]['keyAns'][0] }}. {{ $questionsData[$index]['keyAns'][1] }}
                        </p>
                        
                        <p class="duration-text">Duration: {{ $questionsData[$index]['duration'] }}s</p>
                    </div>
                    
                    @if(!$loop->last)
                    @endif
                </div>
            @endforeach

                
        </div>

                <div class="home-button-wrapper">
                    <a href="{{ route('welcome') }}" class="btn-home">
                        <i class="btn-icon icon-home" alt="Search Icon"></i>
                        <span>Home</span>
                    </a>
                </div>
                
    </div>


                        <!-- see all -->

        @elseif($action === 'all')
            <h2>All Exam Attempt Results for {{ $username }}</h2>
            <table>
                <thead>
                    <tr>
                        <!-- <th>ID</th> -->
                        <th>Date</th>
                        <th>Score</th>
                        <th>Track Percentage</th>
                        <th>Predicted Track</th>
                        <th>Remarks</th>
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
            
                // Inside your <script> at the bottom
                const ctx2 = document.getElementById('doughnutChart').getContext('2d');
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: ['CpE', 'CS', 'IT', 'MMA'],
                        datasets: [{
                            data: [
                                {{ $trackPercentage['Computer Engineering'] }},
                                {{ $trackPercentage['Computer Science'] }},
                                {{ $trackPercentage['Information Technology'] }},
                                {{ $trackPercentage['Multimedia Arts'] }}
                            ],
                            backgroundColor: ['#8b5cf6', '#facc15', '#3b82f6', '#4ade80'],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        cutout: '70%', // Makes it a thin, professional ring
                        plugins: {
                            legend: { display: false } // We use our table as the legend
                        }
                    }
                });
        </script>
    @endif

</body>
</html>
