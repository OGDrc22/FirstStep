<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">
    
    <link rel="stylesheet" href="{{asset('assets/css/flash_message.css')}}?v={{ time() }}">
    <link rel="stylesheet" href="{{asset('assets/css/nav_bar.css')}}?v={{ time() }}">
    <link rel="stylesheet" href="{{asset('assets/css/exam_page.css')}}?v={{ time() }}">

    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body  class="bg-overlay">
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


    
    <div class="main-container">
        <div class="left">
            <div id="timerD"></div>
            <form action="{{ route('submit.exam') }}" method="POST" id="examForm">
                @csrf
                @if (isset($data))
                    <div class="questions">
                        <input type="hidden" name="feedback-input" id="feedback-input-hidden">

                        <input type="hidden" name="job_id" value="{{ $job->id }}">

                        <input type="hidden" name="questionData" id="questionData">
                        <input type="hidden" name="questionText" id="questionText">
                        <input type="hidden" name="category" id="try">
                        
                        @foreach ($data['data']['questions'] as $q)
                            <div class="question-card" data-index="{{ $loop->index }}" data-competencies='@json($q["competencies"])' data-q-type='{{ $q["type"] }}' data-choices-equivalent='@json($q["choice_equivalent"] ?? $q["choices_equivalent"] ?? null)'>
                                
                                @if ($q['type'] !== "Preference")
                                    <h3 class="text_h cat_text">{{ $q['category'] }}</h3>
                                @else
                                    <h3 class="text_h cat_text">Preference</h3>
                                @endif

                                <div class="question-card-a">
                                    <h2 class="qNum">Question {{ $loop->index + 1 }}</h2>
                                    <h3 class="question"> {{ $q['question'] }}</h3>
                                    
                                    
                                    <input type="hidden"
                                        data-category="category[{{ $loop->index }}]"
                                        value="{{ $q['category'] }}" class="category-input">


                                    <ul class="choices-holder">
                                        @foreach ($q['choices'] as $letter => $text)
                                            <li class="choices">
                                                <label>
                                                    <input class="radio" type="radio" name="answer[{{ $loop->parent->index }}]" value="{{ $letter }}">
                                                    <input class="ansText" type="hidden" name="answerText" value="{{ $text }}">
                                                    {{ $letter }}. {{ $text }}
                                                </label>
                                            </li>                         
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <div class="buttons" id="controls">
                        <button type="button" class="btn prev-btn" id="prevBtn"><i class="icon icon-arrow-left"></i> Previous</button>
                        <button type="button" class="btn next-btn" id="nextBtn">Next Question<i class="icon icon-arrow-right"></i></button>
                    </div>
                @else
                    <p>No data available.</p>
                @endif
            </form>
        </div>

        <div class="right">
            <div class="progress-container">
                <h3>Progress</h3>
                <div class="progressbar">
                </div>
            </div>
            <div class="q-nav-container">
                <div class="q-nav-header"><i class="icon icon-grid"></i><h3>Question Navigator</h3></div>
                <div class="question-navigator">
                    @foreach ($data['data']['questions'] as $q)
                        <button type="button" class="nav-q-btn" data-index="{{ $loop->index }}">{{ $loop->index + 1 }}</button>
                    @endforeach    
                </div>
                <div class="q-nav-info">
                    <div class="q-nav-ind"><span class="indicator"></span><h3>Current</h3></div>
                    <div class="q-nav-ind"><span class="indicator"></span><h3>Answered</h3></div>
                    <div class="q-nav-ind"><span class="indicator"></span><h3>Remaining</h3></div>
                    <!-- <div class="q-nav-ind"><span class="indicator"></span><h3>Flagged</h3></div> -->
                </div>
            </div>
            <button class="btn btn-submit" id="btn-submit" type="submit">Finish and Submit Exam</button>
        </div>

        <div class="alert-bg simple-flash hidden">
            <div class="alert">
                <div class="simple-flash-message">
                    <span class="icon icon-danger"></span>
                    <h2>Are you sure you want to submit the exam?</h2>
                    <div class="alert-button-container">
                        <button class="btn btn-secondary"><span class="icon-arrow-right"></span> Cancel</button>
                        <button class="btn btn-primary">Submit <span class="icon-send"></span></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert-bg feedback-form hidden">
            <div class="alert">
                <div class="alert-form">
                    <p>Feedback Form</p>
                    <textarea type="text" class="feedback-input" name="feedback" placeholder="Enter your feedback here..."></textarea>
                    <div class="alert-button-container">
                        <button class="btn btn-secondary"><span class="icon-arrow-right"></span> Cancel</button>
                        <button class="btn btn-middle">Skip <span class="icon-arrow-right"></span></button>
                        <button class="btn btn-primary">Submit <span class="icon-send"></span></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="loading-screen" id="loading-screen">
            <div class="loader-container">
                <span class="loader"></span>
                <p id="text-loader">Submitting Exam.</p>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/exam_page.js') }}"></script>
</body>
</html>
