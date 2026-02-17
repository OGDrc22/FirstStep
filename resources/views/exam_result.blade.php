<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>

    <link rel="stylesheet" href="{{ asset('assets/css/results.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
</head>


<body>
    @if (isset($resData))
        <h3>Score: {{ $correct }} / {{ $totalQuestions }}</h3>
        <div class="questions-review">

        <h3>Recommended Track: {{ $predictedTrack }}</h3>
        <h3>Track Percentage:</h3>

        <div class="pie-container">
            <div class="chart">
                <figure class="pie-chart" style="background:
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

                    );"></figure>
            </div>
            <div class="chart-info">
                <div class="info">
                    <table>
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Track Percentage</th>
                                <th>Accuracy</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div><div style="background-color: violet; width: 32px; height: 16px; border-radius: 4px;"></div>Computer Engineering</div>
                                </td>
                                <td>{{ $trackPercentage['Computer Engineering'] }}%</td>
                                <td>{{ $acc_per_category['Computer Engineering'] * 100 }}%</td>
                                <td>{{ $duration_per_category['Computer Engineering']}} s</td>
                            </tr>
                            <tr>
                                <td>
                                    <div><div style="background-color: yellow; width: 32px; height: 16px; border-radius: 4px;"></div>Computer Science</div>
                                </td>
                                <td>{{ $trackPercentage['Computer Science'] }}%</td>
                                <td>{{ $acc_per_category['Computer Science'] * 100 }}%</td>
                                <td>{{ $duration_per_category['Computer Science']}} s</td>
                            </tr>
                            <tr>
                                <td>
                                    <div><div style="background-color: blue; width: 32px; height: 16px; border-radius: 4px;"></div>Information Technology</div>
                                </td>
                                <td>{{ $trackPercentage['Information Technology'] }}%</td>
                                <td>{{ $acc_per_category['Information Technology'] * 100 }}%</td>
                                <td>{{ $duration_per_category['Information Technology']}} s</td>
                            </tr>
                            <tr>
                                <td>
                                    <div><div style="background-color: lightgreen; width: 32px; height: 16px; border-radius: 4px;"></div>Multimedia Arts</div>
                                </td>
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
                <p class="{{ $isCorrect ? 'bg-correct-a' : 'bg-danger-a' }} stdntAnswer">
                    Your Answer: 
                    @if (isset($questionsData[$index]['answer']))
                        {{  $questionsData[$index]['answer'][0]  }}. {{ $questionsData[$index]['answer'][1] }}
                    @else
                        No Answer
                    @endif
                </p>
                <p class="bg-success-a correctAnswer">Correct Answer: {{ $questionsData[$index]['keyAns'][0] }}. {{ $questionsData[$index]['keyAns'][1] }}</p>
                <p>Duration: {{ $questionsData[$index]['duration'] }}</p>
            </div>
        @endforeach       

        <!-- <h3>System Accuracy: {{ $accuracy }}</h3> -->
    @endif

    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#fff">
        <g clip-path="url(#clip0_4418_7372)">
            <path d="M17.79 22.75H6.21C3.47 22.75 1.25 20.52 1.25 17.78V10.37C1.25 9.00997 2.09 7.29997 3.17 6.45997L8.56 2.25997C10.18 0.999974 12.77 0.939974 14.45 2.11997L20.63 6.44997C21.82 7.27997 22.75 9.05997 22.75 10.51V17.79C22.75 20.52 20.53 22.75 17.79 22.75ZM9.48 3.43997L4.09 7.63997C3.38 8.19997 2.75 9.46997 2.75 10.37V17.78C2.75 19.69 4.3 21.25 6.21 21.25H17.79C19.7 21.25 21.25 19.7 21.25 17.79V10.51C21.25 9.54997 20.56 8.21997 19.77 7.67997L13.59 3.34997C12.45 2.54997 10.57 2.58997 9.48 3.43997Z" fill="white" style="fill: var(--fillg);"/>
            <path d="M12 18.75C11.59 18.75 11.25 18.41 11.25 18V15C11.25 14.59 11.59 14.25 12 14.25C12.41 14.25 12.75 14.59 12.75 15V18C12.75 18.41 12.41 18.75 12 18.75Z" fill="white" style="fill: var(--fillg);"/>
        </g>
        <defs>
            <clipPath id="clip0_4418_7372">
                <rect width="24" height="24" fill="white"/>
            </clipPath>
        </defs>
    </svg>
<a href="{{ route('welcome') }}">Home</a>

    
</body>
</html>