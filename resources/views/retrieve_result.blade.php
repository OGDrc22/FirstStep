<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrieve Result</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/flash_message.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nav_bar.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/results.css') }}?v={{ time() }}">
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
                        <p>View your most recent assessment result, including your recommended career track and performance
                            summary.</p>
                        <button class="p3-btn-action" type="submit" name="action" value="latest">
                            <span>See Laatest</span>
                        </button>
                    </div>
                    <div class="right action-container" style="grid-area: box-3;">
                        <p>See All</p>
                        <p>Browse all your past assessment results to compare your performance and track recommendations
                            over time.</p>
                        <button class="p3-btn-action" type="submit" name="action" value="all">
                            <span>See Results</span>
                        </button>
                    </div>
                </div>
            </form>

        @endif


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
                <div class="card-all-result">
                    <div class="header">
                        <h3>All Exam Attempt Results for {{ $username }}</h3>
                        <p>A comprehensive longitudinal analysis of your academic trajectories and technical aptitude patterns over time.</p>
                    </div>

                    <div class="result-info-container">

                        <div class="result-display-card card">
                            <div class="card-data-row">
                                <span class="card-label">Primary Recommendation</span>
                                <span class="card-value highlight">{{ $recommendedTrack }}</span>
                                <p> {{ $note }}</p>
                            </div>

                            <div class="card-data-row">
                                <span class="card-label">Seondary Recommendation</span>
                                <span class="card-value highlight">{{ $secondaryTrack }}</span>
                            </div>
                        </div>
                        <!-- <div class="info-tracks">
                            <h3>Recommended track based on all exam attempt:</h3>
                            <h4>{{ $recommendedTrack }}</h4>
                        </div> -->

                        <!-- <div class="chart-bar">
                            <canvas id="stackedLineChart"></canvas>
                        </div> -->
                        <div class="left-chart card">
                            <div class="chart-label">
                                <h3>Track Breakdown</h3>
                                <i class="icon icon-chart" alt="Chart Icon"></i>
                            </div>
                            <div class="chart-bar" style="position: relative;">
                                <!-- <div style="display: flex; position: relative; height: 250px;"> -->

                                <!-- LEFT: Labels -->
                                <div id="custom-labels">
                                </div>

                                <!-- RIGHT: Chart -->
                                <div class="canvas-wrapper">
                                    <canvas id="stackedLineChart"></canvas>
                                </div>

                                <!-- </div> -->
                            </div>
                        </div>
                    </div>


                    <div class="results-table">
                        <h3>Previous Assessments Attempts</h3>
                        
                        <table>
                            <thead>
                                <tr>
                                    <!-- <th>ID</th> -->
                                    <!-- <th>Track Percentage</th> -->
                                    <th>Date</th>
                                    <th>Predicted Track</th>
                                    <th>Secondary Track</th>
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
                                        <td>{{ $exam->created_at->format('M d, Y: h:i') }}</td>

                                        @if ($exam->predicted_track['track'] == "Information Technology")
                                            <td><p class="track_it">{{ $exam->predicted_track['track'] }}</p></td>
                                        @elseif ($exam->predicted_track['track'] == "Computer Science")
                                            <td><p class="track_cs">{{ $exam->predicted_track['track'] }}</p></td>
                                        @elseif ($exam->predicted_track['track'] == "Computer Engineering")
                                            <td><p class="track_ce">{{ $exam->predicted_track['track'] }}</p></td>
                                        @elseif ($exam->predicted_track['track'] == "Multimedia Arts")
                                            <td><p class="track_mma">{{ $exam->predicted_track['track'] }}</p></td>
                                        @endif

                                        <td>{{ $exam->secondary_track['track'] }}</td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <div class="footer">
        <p>© 2026 Philippine Christian University - College of Informatics. All rights reserved.</p>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>


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


            const tracks = @json($computedTrackPercentage);

            const filteredTracksObj = Object.entries(tracks).filter(([_, value]) => value > 0);
            console.log(
                filteredTracksObj
            );
            
            const labels = filteredTracksObj.map(([key, item]) => key);
            const percentage = filteredTracksObj.map(([key, item]) => item.toFixed(2));

            // const filteredAcc = Object.entries(averageAcc).filter(([key, item]) => item.0);
            


            const ctx = document.getElementById('stackedLineChart').getContext('2d');

                    
            const data = {
                labels: labels,
                datasets: [{
                    axis: 'y',
                    label: labels,
                    data: percentage,
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
                        datalabels: { display: false },
                        // Tooltip
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    // Main Bar Label
                                    return `Total Score: ${context.parsed.x.toFixed(2)}%`;
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
            new Chart(ctx, config);

            // Custom labels
            const labelsContainer = document.getElementById('custom-labels');

            labelsContainer.innerHTML = labels.map((label, index) => {
                return `
                    <div style="flex:1;display:flex;justify-content:space-between;padding-left:10px;">
                        <span>${label}</span>
                        <span>${percentage[index]} %</span>
                    </div>
                `;
            }).join('');  
        </script>

    @endif

</body>

</html>