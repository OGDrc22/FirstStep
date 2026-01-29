<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrieve Result</title>

    <link rel="stylesheet" href="{{ asset('assets/css/results.css') }}">
</head>
<body>
    
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
            <h2>Exam Result for {{ $username }}</h2>
            <h3>Score: {{ $examResult->score }}</h3>
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
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div><div style="background-color: violet; width: 32px; height: 16px; border-radius: 4px;"></div>Computer Engineering</div>
                                    </td>
                                    <td>{{ $trackPercentage['Computer Engineering'] }}%</td>
                                    <td>{{ $examResult['accuracy_per_category']['CE'] }}%</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div><div style="background-color: yellow; width: 32px; height: 16px; border-radius: 4px;"></div>Computer Science</div>
                                    </td>
                                    <td>{{ $trackPercentage['Computer Science'] }}%</td>
                                    <td>{{ $examResult['accuracy_per_category']['CS'] }}%</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div><div style="background-color: blue; width: 32px; height: 16px; border-radius: 4px;"></div>Information Technology</div>
                                    </td>
                                    <td>{{ $trackPercentage['Information Technology'] }}%</td>
                                    <td>{{ $examResult['accuracy_per_category']['IT'] }}%</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div><div style="background-color: lightgreen; width: 32px; height: 16px; border-radius: 4px;"></div>Multimedia Arts</div>
                                    </td>
                                    <td>{{ $trackPercentage['Multimedia Arts'] }}%</td>
                                    <td>{{ $examResult['accuracy_per_category']['MMA'] }}%</td>
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

            @foreach ($qData as $index => $q)
                    <div class="question-review-card">
                        <h4 class="question-review">{{ $q['questionText'] }}</h4>
                        <p>Your Answer: 
                            @if (isset($q['answer']))
                                {{ $q['answer'] }}
                            @else
                                No Answer
                            @endif
                        </p>
                        <p>Correct Answer: {{ $q['keyAnswer'] }}</p>
                        <p>Duration: {{ $q['duration'] }}s</p>
                    </div>
                @endforeach


        @elseif($action === 'all')
            <h2>All Exam Attempt Results for {{ $username }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Track Percentage</th>
                        <th>Predicted Track</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($examResult as $exam)
                        <tr>
                            <td>{{ $exam->created_at->format('M d, Y: h:i') }}</td>
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
        @endif
    @endif
    <a href="{{ route('welcome') }}">Home</a>
</body>
</html>