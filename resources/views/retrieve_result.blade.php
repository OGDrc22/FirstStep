<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrieve Result</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/flash_message.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nav_bar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/results.css') }}">
</head>



<body class="bg-overlay">

    <nav class="top-nav-pcu">
        <a class="top-nav-left" href="{{ route('welcome') }}">
            <img class="pcu-logo" src="{{ asset('assets/images/main_logo.png') }}" alt="PCU Logo">
            <span class="pcu-text-small">Philippine Christian University</span>
        </a>
        <a class="top-nav-right" href="{{ route('welcome') }}">
            <span class="coi-text">COI First Step</span>
            <img class="pcu-coi-logo" src="{{ asset('assets/images/College_of_Informatics_72_R.png') }}" alt="COI Logo">
        </a>
    </nav>

    <div class="content">
        @if ($errors->has('email'))
            <div class="alert heads-up-message hum-error">
                <i class="icon-close"></i>
                {{ $errors->first('email') }}
            </div>
        @endif


        @if (!isset($examResult))
            <!--entering -->
            <form method="POST" action="/get-result">
                @csrf
                <div class="login-page">
                    <div class="top-content" style="grid-area: box-1;">
                        <p>Start Your Assessment</p>
                        <div class="email-input input-container">
                            <label for="name">Username</label>
                            <input type="email" id="email" name="email" placeholder="Email@gmail.com" required>
                        </div>
                    </div>
                    <div class="left action-container" style="grid-area: box-2;">
                        <p>See Latest</p>
                        <p>View your most recent assessment result, including your recommended career track and performance summary.</p>
                        <button class="p3-btn-action" type="submit" name="action" value="latest" >
                            <span>See Laatest</span>
                        </button>
                    </div>
                    <div class="right action-container" style="grid-area: box-3;">
                        <p>See All</p>
                        <p>Browse all your past assessment results to compare your performance and track recommendations over time.</p> 
                        <button class="p3-btn-action" type="submit" name="action" value="all" >
                            <span>See Results</span>
                        </button>
                    </div>
                </div>
            </form>
            <!-- <div class="page-Cencontainer">
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
            </div> -->
        @endif
<!-- 
                <div class="home-btn-container">
                    <a href="{{ route('welcome') }}" class="home-link"><i class="icon-home"></i> Back to Home</a>
                </div> -->

        <!-- score board> -->

        @if (isset($examResult))
            @if ($action === 'latest')
                <h3 class="result-title">Assessment Result</h3>

                <!-- <div class="card-data-row">
                    <span class="card-label">Score:</span>
                    <span class="card-value">{{ $examResult->score }}</span>
                </div> -->

                
                
                <div class="dashboard-grid">
                    <div class="user-card card">
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
                    <div class="aptitude-card card">
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
                                <div id="custom-labels">
                                </div>

                                <!-- RIGHT: Chart -->
                                <div class="canvas-wrapper">
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

                

                <div class="questions-review card">
                    <h3>Question Review</h3>
                    
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

                <!-- see all -->

            @elseif($action === 'all')
                <h2>All Exam Attempt Results for {{ $username }}</h2>
                <table>
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <!-- <th>Track Percentage</th> -->
                            <th>Predicted Track</th>
                            <th>Secondary Track</th>
                            <th>Remarks</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($examResult as $exam)
                            <tr onclick="window.location='{{ route('show-exam-result', $exam->id) }}'" style="cursor: pointer">
                                <!-- <td>{{ $exam->id }}</td> -->
                                <!-- <td>
                                    @foreach ($exam->sorted_tracks as $percentage)
                                        {{ $percentage['track'] }}: {{ $percentage['percentage'] }}% <br>
                                    @endforeach
                                </td> -->
                                <td>{{ $exam->predicted_track['track'] }}</td>

                                <td>{{ $exam->secondary_track['track'] }}</td>

                                <td>{{ $exam->evaluation_note }}</td>

                                <td>{{ $exam->created_at->format('M d, Y: h:i') }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h3>Recommended track based on all exam attempt:</h3>
                <h4>{{ $recommendedTrack }}</h4>

                <div class="chart-bar">
                    <canvas id="stackedLineChart"></canvas>
                </div>
            @endif
        @endif
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>


    @if (isset($examResult) && $action === 'latest')
        <script>
            const trackPercentage = @json($trackPercentage);
            const detailedCompetencies = @json($detailedCompetencyLevels);
            const coreCompetencies = @json($coreCompetencies);

            document.addEventListener('DOMContentLoaded', function () {
                initCharts(trackPercentage, detailedCompetencies, coreCompetencies);
            });
        </script>

        <script src="{{ asset('assets/js/charts.js') }}"></script>
    @elseif (isset($examResult) && $action === 'all')
        <script>


            const tracks = @json($trackPercentage);
            const filterTracks = Object.entries(tracks).filter(([key, item]) => item.percentage > 0);
            const labels = filterTracks.map(([key, item]) => item.track);
            const percentage = filterTracks.map(([key, item]) => item.percentage);


            const ctx = document.getElementById('stackedLineChart').getContext('2d');

            Chart.defaults.font.size = 16;
            const data = {
                labels: labels,
                datasets: [{
                    axis: 'y',
                    label: 'Track Percentage',
                    data: percentage,
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
            new Chart(ctx, config);    
        </script>	

    @endif

</body>

</html>