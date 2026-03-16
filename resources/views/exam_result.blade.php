<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>

    <link rel="stylesheet" href="{{ asset('assets/css/results.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flash_message.css') }}">
</head>


<body>
    @if (isset($success))
        <div class="alert heads-up-message hum-success">
            <p class="p p-success">Your feedback has been submitted successfully!</p>
        </div>
    @endif

    @if (isset($resData))
        <h3>Score: {{ $correct }} / {{ $totalQuestions }}</h3>
        <div class="questions-review">

            <h3>Recommended Track: {{ $predictedTrack }}</h3>
            <h3>Track Percentage:</h3>

            <div class="pie-container">
                <div class="chart">
                    <canvas id="doughnutChart">
                    </canvas>
                </div>
                <div class="chart-info">
                    <div class="info">
                        <table>
                            <thead>
                                <tr>
                                    <!-- <th>Category</th> -->
                                    <th>Track Percentage</th>
                                    <th>Accuracy</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <!-- <td>
                                        <div><div style="background-color: violet; width: 32px; height: 16px; border-radius: 4px;"></div>Computer Engineering</div>
                                    </td> -->
                                    <td>{{ $trackPercentage['Computer Engineering'] }}%</td>
                                    <td>{{ $acc_per_category['Computer Engineering'] * 100 }}%</td>
                                    <td>{{ $duration_per_category['Computer Engineering']}} s</td>
                                </tr>
                                <tr>
                                    <!-- <td>
                                        <div><div style="background-color: yellow; width: 32px; height: 16px; border-radius: 4px;"></div>Computer Science</div>
                                    </td> -->
                                    <td>{{ $trackPercentage['Computer Science'] }}%</td>
                                    <td>{{ $acc_per_category['Computer Science'] * 100 }}%</td>
                                    <td>{{ $duration_per_category['Computer Science']}} s</td>
                                </tr>
                                <tr>
                                    <!-- <td>
                                        <div><div style="background-color: blue; width: 32px; height: 16px; border-radius: 4px;"></div>Information Technology</div>
                                    </td> -->
                                    <td>{{ $trackPercentage['Information Technology'] }}%</td>
                                    <td>{{ $acc_per_category['Information Technology'] * 100 }}%</td>
                                    <td>{{ $duration_per_category['Information Technology']}} s</td>
                                </tr>
                                <tr>
                                    <!-- <td>
                                        <div><div style="background-color: lightgreen; width: 32px; height: 16px; border-radius: 4px;"></div>Multimedia Arts</div>
                                    </td> -->
                                    <td>{{ $trackPercentage['Multimedia Arts'] }}%</td>
                                    <td>{{ $acc_per_category['Multimedia Arts'] * 100 }}%</td>
                                    <td>{{ $duration_per_category['Multimedia Arts']}} s</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Can be used as Answer Review -->
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
                        {{ $questionsData[$index]['keyAns'][1] }}</p>
                    <p>Duration: {{ $questionsData[$index]['duration'] }}</p>
                </div>
            @endforeach

            <!-- <h3>System Accuracy: {{ $accuracy }}</h3> -->
    @endif


        <a href="{{ route('welcome') }}">Home</a>

        <script src="{{ asset('assets/js/flash_message.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js"></script>

        <script>

            const ctx2 = document.getElementById('doughnutChart').getContext('2d');
            Chart.defaults.font.size = 16;
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
                        label: 'Track Percentage',
                        data: [
                        {{ $trackPercentage['Computer Engineering'] }},
                        {{ $trackPercentage['Computer Science'] }},
                        {{ $trackPercentage['Information Technology'] }},
                            {{ $trackPercentage['Multimedia Arts'] }}
                        ],
                        backgroundColor: [
                            'rgb(100, 0, 130)',
                            'rgb(255, 205, 86)',
                            'rgb(54, 162, 235)',
                            'rgb(125, 255, 125)'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Track Percentage'
                        }
                    }
                }
            });
        </script>

</body>

</html>