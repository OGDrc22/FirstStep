<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/exam_page.css')}}">
    <!-- <link rel="stylesheet" href="{{asset('assets/css/exam_style.css')}}"> -->

    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="header-pcu">
        <img class="pcu-logo" src="{{ asset('assets/images/main_logo.png') }}" alt="" srcset="">
        <a class="pcu-text">PCU - COI First Step</a>
        <img class="pcu-coi-logo" src="{{ asset('assets/images/College_of_Informatics_72_R.png') }}" alt="" srcset="">
    </div>


    <!-- <div id="loading-screen">
        <div class="spinner"></div>
        <p>Loading Result...</p>
    </div> -->
    
    <div class="main-container">
        <div class="left">
            <div id="timerD"></div>
            <form action="{{ route('submit.exam') }}" method="POST" id="examForm">
                @csrf
                @if (isset($data))
                    <div class="questions">
                        <input type="hidden" name="job_id" value="{{ $job->id }}">

                        <input type="hidden" name="questionData" id="questionData">
                        <input type="hidden" name="questionText" id="questionText">
                        <input type="hidden" name="category" id="try">
                        @foreach ($data['data']['questions'] as $q)
                            <div class="question-card" data-index="{{ $loop->index }}">
                                <h3 class="question">{{ $loop->index + 1 }}. {{ $q['question'] }}</h3>
                                <h3 class="text">{{ $q['category'] }}</h3>
                                
                                
                                <input type="hidden"
                                    data-category="category[{{ $loop->index }}]"
                                    value="{{ $q['category'] }}" class="category-input">


                                <ul class="choices-holder">
                                    @foreach ($q['choices'] as $letter => $text)
                                        <li class="choices">
                                            <label>
                                                <input class="radio" type="radio" name="answer[{{ $loop->parent->index }}]" value="{{ $letter }}">
                                                {{ $letter }}. {{ $text }}
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
                    <p>No data available.</p>
                @endif
            </form>
        </div>

        <div class="right">
            <h3>Question Navigator</h3>
            <div class="question-navigator">
                @foreach ($data['data']['questions'] as $q)
                    <button type="button" class="nav-q-btn" data-index="{{ $loop->index }}">{{ $loop->index + 1 }} Question</button>
                @endforeach
            </div>
        </div>

    </div>

    <script src="{{ asset('assets/js/exam_page.js') }}"></script>
</body>
</html>