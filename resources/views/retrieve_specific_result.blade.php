<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="{{ asset('assets/css/results.css') }}">
</head>

<body>
    <h2>Exam Result for {{ $username }}</h2>
    <h3>Score: {{ $examResult->score }}</h3>
    <h3>Highest %: {{ $predictedTrack }}%</h3>
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
                        </tr>
                    </thead>
                    <tbody>

                        
                        <tr>
                            <td>
                                <div>
                                    <div
                                        style="background-color: violet; width: 32px; height: 16px; border-radius: 4px;">
                                    </div>Computer Engineering
                                </div>
                            </td>
                            <td>{{ $trackPercentage['Computer Engineering'] }}%</td>
                            <td>{{ $acc_per_category['Computer Engineering'] * 100 }}%</td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <div
                                        style="background-color: yellow; width: 32px; height: 16px; border-radius: 4px;">
                                    </div>Computer Science
                                </div>
                            </td>
                            <td>{{ $trackPercentage['Computer Science'] }}%</td>
                            <td>{{ $acc_per_category['Computer Science'] * 100 }}%</td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <div style="background-color: blue; width: 32px; height: 16px; border-radius: 4px;">
                                    </div>Information Technology
                                </div>
                            </td>
                            <td>{{ $trackPercentage['Information Technology'] }}%</td>
                            <td>{{ $acc_per_category['Information Technology'] * 100 }}%</td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <div
                                        style="background-color: lightgreen; width: 32px; height: 16px; border-radius: 4px;">
                                    </div>Multimedia Arts
                                </div>
                            </td>
                            <td>{{ $trackPercentage['Multimedia Arts'] }}%</td>
                            <td>{{ $acc_per_category['Multimedia Arts'] * 100 }}%</td>
                        </tr>
                    </tbody>
                </table>
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
            <p>Your Answer:
                @if (isset($questionsData[$index]['answer']))
                    {{ $questionsData[$index]['answer'] }}
                @else
                    No Answer
                @endif
            </p>
            <p>Correct Answer: {{ $questionsData[$index]['keyAns'] }}</p>
            <p>Duration: {{ $questionsData[$index]['duration'] }}s</p>
        </div>
    @endforeach
    
    <a href="{{ url('/') }}">Home</a>

</body>

</html>