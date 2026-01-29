<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
</head>
<body>
    @if (isset($examResult))
        <h3>Latest exam attempt result</h3>
        <h3>Score: {{ $examResult['score'] }}</h3>
        <h3>Total: {{ $examResult['total'] }}</h3>
        <div class="questions-review">

        <!-- Can be used as Answer Review -->
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
                    <p>Duration: {{ $q['duration'] }}</p>
                </div>
            @endforeach
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
                    <div><div style="background-color: violet; width: 32px; height: 16px; border-radius: 4px;"></div> Computer Engineering: {{ $trackPercentage['Computer Engineering'] }}%</div>
                
                    <div><div style="background-color: yellow; width: 32px; height: 16px; border-radius: 4px;"></div> Computer Science: {{ $trackPercentage['Computer Science'] }}%</div>
            
                    <div><div style="background-color: blue; width: 32px; height: 16px; border-radius: 4px;"></div> Information Technology: {{ $trackPercentage['Information Technology'] }}%</div>
                
                    <div><div style="background-color: lightgreen; width: 32px; height: 16px; border-radius: 4px;"></div> Multimedia Arts: {{ $trackPercentage['Multimedia Arts'] }}%</div>
                </div>
            </div>
        </div>

        <div class="progress-flex" style="background-color: gray">
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
        </div>

        <!-- <h3>Computer Engineering: {{ $trackPercentage['Computer Engineering'] }}%</h3>
        <div class="progress" style="background-color: gray">
            <div class="percent" style="width: {{ $trackPercentage['Computer Engineering'] }}vw; background-color: violet;"></div>
        </div>
        <h3>Computer Science: {{ $trackPercentage['Computer Science'] }}%</h3>
        <div class="progress" style="background-color: gray">
            <div class="percent" style="width: {{ $trackPercentage['Computer Science'] }}vw; background-color: yellow;"></div>
        </div>
        <h3>Information Technology: {{ $trackPercentage['Information Technology'] }}%</h3>
        <div class="progress" style="background-color: gray">
            <div class="percent" style="width: {{ $trackPercentage['Information Technology'] }}vw; background-color: blue;"></div>
        </div>
        <h3>Multimedia Arts: {{ $trackPercentage['Multimedia Arts'] }}%</h3>
        <div class="progress" style="background-color: gray">
            <div class="percent" style="width: {{ $trackPercentage['Multimedia Arts'] }}vw; background-color: lightgreen;"></div>
        </div> -->

        <h3>System Accuracy: {{ $accuracy }}</h3>
    @endif
</body>
</html>