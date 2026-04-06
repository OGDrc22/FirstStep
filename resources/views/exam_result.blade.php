<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/results.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flash_message.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nav_bar.css') }}">
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
                <!-- <h3>System Accuracy: {{ $model_accuracy }}</h3> -->
        @endif
    </div>


    <a href="{{ route('welcome') }}">Home</a>

    <script src="{{ asset('assets/js/flash_message.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>

    <script>
        const trackPercentage = @json($trackPercentage);
        const detailedCompetencies = @json($detailedCompetencyLevels);
        const coreCompetencies = @json($coreCompetencies);

        document.addEventListener('DOMContentLoaded', function () {
            initCharts(trackPercentage, detailedCompetencies, coreCompetencies);
        });
    </script>

    <script src="{{ asset('assets/js/charts.js') }}"></script>

</body>

</html>