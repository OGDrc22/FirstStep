<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/start_exam.css')}}">
    <!-- <link rel="stylesheet" href="{{asset('assets/css/exam_style.css')}}"> -->
</head>
<body>
    <div class="header-pcu">
        <img class="pcu-logo" src="{{ asset('assets/images/main_logo.png') }}" alt="" srcset="">
        <a class="pcu-text">PCU - COI First Step</a>
        <img class="pcu-coi-logo" src="{{ asset('assets/images/College_of_Informatics_72_R.png') }}" alt="" srcset="">
    </div>

    
    <div class="main-container">
        <div class="left">
            <div id="timerD"></div>
            <div id="timerDF"></div>
            <form action="{{ route('submit-exam') }}" method="POST">
                @csrf
                @if (isset($data))
                    <div class="questions">    
                        @foreach ($data['data'] as $q)
                            <div class="question-card" data-index="{{ $loop->index }}">
                                <h3 class="question">{{ $loop->index + 1 }}. {{ $q[0] }}</h3>
                                <ul class="choices-holder">
                                    @foreach ($q[1] as $choises)
                                        @php
                                            $parts = preg_split('/\.\s*/', $choises, 2);
                                            $letter = isset($parts[0]) ? trim($parts[0]) : '';
                                            $text = isset($parts[1]) ? trim($parts[1]) : '';
                                        @endphp
                                        <li class="choices">
                                            <label>
                                                <input class="radio" type="radio" name="answer[{{ $loop->parent->index }}]" value="{{ $letter }}"> {{ $letter }}. {{ $text }}
                                            </label>
                                        </li>                                
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>

                    <div id="controls">
                        <button class="btn-controls" id="prevBtn" type="button">Prev</button>
                        <button class="btn-controls" id="nextBtn" type="button">Next</button>
                        <button class="btn-controls" id="submit" type="submit">Submit</button>
                    </div>
                @else
            </form>
                <p>No data available.</p>
            @endif
        </div>

        <div class="right">
            <h3>Question Navigator</h3>
            <div class="question-navigator">
                @foreach ($data['data'] as $q)
                    <button class="nav-q-btn" data-index="{{ $loop->index }}">{{ $loop->index + 1 }} Question</button>
                @endforeach
            </div>
        </div>

    </div>

    <script src="{{ asset('assets/js/start_exam.js') }}"></script>
</body>
</html>