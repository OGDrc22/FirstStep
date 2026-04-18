<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Retrieve Result</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/flash_message.css') }}?v={{ filemtime('assets/css/flash_message.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nav_bar.css') }}?v={{ filemtime('assets/css/nav_bar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/results.css') }}?v={{ filemtime('assets/css/results.css') }}">
</head>

<style>
    @import url("{{ asset('assets/css/icons.css') }}?v={{ filemtime(public_path('assets/css/icons.css')) }}");
</style>



<body class="bg-overlay" id="target-content">

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
        @if ($errors->has('email_err'))
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    Toast.create(document.body, "err", @json($errors->first('email_err')));
                });
            </script>
        @endif


        @if (!isset($examResult))
            <!--entering -->
            <div class="login-form-container">
                <form method="POST" action="/get-result">
                    @csrf
                    <div class="login-page">
                        <div class="top-content" style="grid-area: box-1;">
                            <p>Review Your Assessment</p>
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
                                <span>See Latest</span>
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
            </div>

        @endif


        @if (isset($examResult))
            @if ($action === 'latest')

                <div class="dashboard-grid">
                    <h3 class="result-title">Assessment Result</h3>                    

                    <div class="user-card card">
                        <div class="user-detail">
                            <i class="icon icon-user" alt="User Icon"></i>
                            <span class="user-name">{{ $username }}</span>
                        </div>
                        <div class="user-detail">
                            <i class="icon icon-email" alt="Email Icon"></i>
                            <span class="user-email">{{ $useremail }}</span>
                        </div>
                    </div>
                    <div class="btn-container-result">
                        <button type="submit" class="btn-sendEmail" id="sendEmail" data-url="{{ route('send.result.email') }}" data-token="{{ csrf_token() }}"><i class="icon icon-email" alt="Email Icon"></i> Send a Copy to Email</button>
                        <button type="submit" class="btn-sendEmail" id="send-qr" data-url="{{ uri('/generate-qr-link') }}" data-token="{{ csrf_token() }}"><i class="icon icon-grid" alt="Email Icon"></i> Get a Copy via QR Code</button>
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
                            <i class="icon icon-chart" alt="Chart Icon"></i>
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
                            <i class="icon icon-pie" alt="Pie Chart Icon"></i>
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
                                                    Informati
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
                                $studentAnswerArray = $questionsData[$index]['answer'] ?? [];
                                $correctAnswerArray = $questionsData[$index]['keyAns'] ?? [];

                                $studentAns = $studentAnswerArray[0] ?? null;
                                $correctAns = $correctAnswerArray[0] ?? null;

                                $isCorrect = $studentAns !== null && $correctAns !== null && $studentAns === $correctAns;

                                $fullStudentAns = isset($studentAnswerArray[0], $studentAnswerArray[1])
                                    ? $studentAnswerArray[0] . ". " . $studentAnswerArray[1]
                                    : 'No Answer';

                                $fullCorrectAns = isset($correctAnswerArray[0], $correctAnswerArray[1])
                                    ? $correctAnswerArray[0] . ". " . $correctAnswerArray[1]
                                    : 'No Correct Answer';
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
                    <div class="top-container">
                        <div class="header">
                            <h3>Assessment History and Results for {{ $username }}</h3>
                            <p>A comprehensive longitudinal analysis of your academic trajectories and technical aptitude patterns over time.</p>
                        </div>

                        <div class="btn-container-result">
                            <button type="submit" class="btn-sendEmail" id="sendEmail" data-url="{{ route('send.result.email') }}" data-token="{{ csrf_token() }}"><i class="icon icon-email" alt="Email Icon"></i> Send a Copy to Email</button>
                            <button type="submit" class="btn-sendEmail" id="send-qr" data-url="{{ uri('/generate-qr-link') }}" data-token="{{ csrf_token() }}"><i class="icon icon-grid" alt="Email Icon"></i> Get a Copy via QR Code</button>
                        </div>
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

                    <div class="info-card card">
                        <div class="header">
                            <div class="icon-container" style="grid-area: icon;">
                                <i class="icon icon-think"></i>
                            </div>
                            <div class="texts">
                                <h3 style="grid-area: title;">How Your Results is Calculated</h3>
                                <h2 style="grid-area: sub-title;">All results are based on a combination of performance metrics and machine learning predictions.</h2>
                            </div>
                        </div>
                        <div class="cards-container">
                            <div class="sub-card">
                                <div class="icon-container">
                                    <i class="icon icon-document-copy"></i>
                                </div>
                                <h2>Aptitude Tests</h2>
                                <h1>Standardized assessments measuring problem-solving, logic, and technical ability.</h2>
                            </div>
                            <div class="sub-card">
                                <div class="icon-container">
                                    <i class="icon icon-chart"></i>
                                </div>
                                <h2>Core Competencies</h2>
                                <h1>Evaluation of your hard skills across logic, syntax, and system design.</h1>
                            </div>
                            <div class="sub-card">
                                <div class="icon-container">
                                    <i class="icon icon-heart"></i>
                                </div>
                                <h2>Career Interest</h2>
                                <h1>Profiling your professional preferences and work-style inclinations.</h1>
                            </div>
                            <div class="sub-card">
                                <div class="icon-container">
                                    <i class="icon icon-bot"></i>
                                </div>
                                <h2>Weighted Model</h2>
                                <h1>• 60% User Performance <br> (Accuracy, completion, behavior) <br> <br> • 40% Machine Learning Prediction <br> (Pattern-based recommendation).</h1>
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
                                    <th>Secondary Recommendation</th>
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

    <div id="qrModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; justify-content:center; align-items:center; flex-direction:column; backdrop-filter: blur(4px);">
        <div style="background:white; padding:30px; border-radius:20px; text-align:center; width: 320px;">
            <h3 style="color:#07182f; margin-bottom:10px;">Scan to Download</h3>
            <p style="font-size:12px; color:#666; margin-bottom:20px;">Your report has been captured. Scan below to save the image to your phone.</p>
            
            <div id="qrcode_canvas" style="margin-bottom:20px; padding:10px; background:#f4f4f4; border-radius:10px; display:inline-block;"></div>
            
            <button onclick="document.getElementById('qrModal').style.display='none'" 
                    style="background:#2D79C1; color:white; border:none; padding:12px; border-radius:8px; cursor:pointer; width:100%; font-weight:bold;">
                Done
            </button>
        </div>
    </div>

    <div class="footer">
        <p>© 2026 Philippine Christian University - College of Informatics. All rights reserved.</p>
    </div>


    <script src="{{ asset('assets/js/flash_message.js') }}"></script>
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


            const cTracks = @json($computedTrackPercentage);

            const filteredTracksObj = Object.entries(cTracks).filter(([_, value]) => value > 0);
            
            const labels = filteredTracksObj.map(([key, item]) => key);
            const percentage = filteredTracksObj.map(([key, item]) => item.toFixed(2));

            const trackPercentage = @json($trackPercentage);
            const filteredTracksPer = Object.entries(trackPercentage).filter(([_, value]) => value);
            console.log(
                filteredTracksPer
            );
            const newTrack = filteredTracksPer.map(([key, value]) => value);
            console.log(
                newTrack
            );
            const rawP = newTrack.map(item => item.percentage);
            console.log(
                rawP
            );

            const averageAcc = @json($averageAcc);
            const filteredAcc = Object.entries(averageAcc).filter(([key, item]) => key);
            const average = filteredAcc.map(([key, item]) => item.toFixed(2));
            console.log(
                average
            );


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
                        tooltip: {
                            enabled: false
                        }
                        // tooltip: {
                        //     callbacks: {
                        //         label: function(context) {
                        //             // Main Bar Label
                        //             return `Total Score: ${context.parsed.x.toFixed(2)}%`;
                        //         },
                        //         afterLabel: function(context) {
                        //             // const averageLabel = context.label;
                        //             let lines = [];
                        //             // if(tracks[key]) {
                        //             //     const nameL = "Average Accuracy";
                        //             //     const avrg = average[key];
                        //             //     lines.push(`$(name): $(avrg)`);
                        //             // }

                        //             const index = context.dataIndex;
                        //             const rawTP = rawP[index];
                        //             const avrg = average[index];
                                    
                        //             if (avrg !== undefined) {
                        //                 lines.push(`Raw Percentage: ${rawTP}%`);
                        //                 lines.push(`Average Accuracy: ${avrg}%`);
                        //                 return lines;
                        //             }

                        //         }
                        //     }
                        // }
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script src="{{ asset('assets/js/result.js') }}"></script>

</body>

</html>