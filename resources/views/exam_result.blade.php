<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Result</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/results.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flash_message.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nav_bar.css') }}?v={{ time() }}">
</head>


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


    @if (isset($success))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Toast.create(document.body, "success", "Your feedback has been submitted successfuly!");
            });
        </script>
    @endif

    <div class="content">
        @if (isset($resData))

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
                    <button type="submit" class="btn-sendCopy" id="sendEmail" data-url="{{ route('send.result.email.from.exam') }}" data-token="{{ csrf_token() }}"><i class="icon icon-email" alt="Email Icon"></i> Send a Copy to Email</button>
                    <button type="submit" class="btn-sendCopy" id="send-qr" data-url="{{ uri('/generate-qr-link') }}" data-token="{{ csrf_token() }}"><i class="icon icon-grid" alt="Email Icon"></i> Get a Copy via QR Code</button>
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

    
    <div id="qrModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; justify-content:center; align-items:center; flex-direction:column; backdrop-filter: blur(4px);">
        <div style="background:white; padding:30px; border-radius:20px; text-align:center; width: 320px;">
            <h3 style="color:#07182f; margin-bottom:10px;">Scan to Download</h3>
            <p style="font-size:12px; color:#666; margin-bottom:20px;">Your report has been captured. Scan below to save the image to your phone.</p>
            
            <div id="qrcode_canvas" style="margin-bottom:20px; padding:10px; background:#f4f4f4; border-radius:10px; display:inline-block;"></div>
            
            <button id="modal-btn-done" 
                    style="background:#2D79C1; color:white; border:none; padding:12px; border-radius:8px; cursor:pointer; width:100%; font-weight:bold;">
                Done
            </button>
        </div>
    </div>

    <a href="{{ route('welcome') }}">Home</a>

    <script src="{{ asset('assets/js/flash_message.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>

    <script>
        const trackPercentage = @json($trackPercentage);
        const detailedCompetencies = @json($detailedCompetencyLevels);
        const coreCompetencies = @json($coreCompetencies);

        document.addEventListener('DOMContentLoaded', function () {
            initCharts(trackPercentage, detailedCompetencies, coreCompetencies);
        });
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script src="{{ asset('assets/js/charts.js') }}"></script>
    <script src="{{ asset('assets/js/result.js') }}"></script>

</body>

</html>