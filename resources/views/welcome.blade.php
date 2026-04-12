<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCU First Step</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">

    <link rel="stylesheet" href="{{asset('assets/css/nav_bar.css')}}?v={{ time() }}">
    <link rel="stylesheet" href="{{asset('assets/css/results.css')}}?v={{ time() }}">
    <link rel="stylesheet" href="{{asset('assets/css/icons.css')}}?v={{ time() }}">
    <link rel="stylesheet" href="{{asset('assets/css/flash_message.css')}}?v={{ time() }}">
    <link rel="stylesheet" href="{{asset('assets/css/welcome.css')}}?v={{ time() }}">
</head>

<body class="bg-overlay">

    @if (session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Toast.create(document.body, "err", @json(session('error')));
            });
        </script>
    @endif
    <!-- <div class="alert-bg">
        <div class="alert alert-danger">
            Sample Error Message
        </div>
    </div> -->

    <!-- <div class="">
        <div class="heads-up-message">
            Sample Error Message
        </div>
    </div> -->


    <nav class="top-nav-pcu">
        <a class="top-nav-left" href="{{ route('welcome') }}">
            <img class="pcu-logo" src="{{ asset('assets/images/main_logo.png') }}" alt="PCU Logo">
            <span class="pcu-text-small">Philippine Christian University</span>
        </a>
        <!-- <a class="top-nav-right" href="{{ route('welcome') }}">
            <span class="coi-text">COI First Step</span>
            <img class="pcu-coi-logo" src="{{ asset('assets/images/College_of_Informatics_72_R.png') }}" alt="COI Logo">
        </a> -->
        <div class="nav-right-btns">
            <a href="{{ url("/retrieve-result") }}" class="btn-action-nav">
                <!-- <i class="btn-icon icon-search-status" alt="Search Icon"></i> -->
                <span>See Results</span>
            </a>
            <a href="{{ url("/assessment-entry") }}" class="btn-action-nav">
                <!-- <i class="btn-icon icon-book"></i> -->
                <span>Start Exam</span>
            </a>
        </div>
    </nav>


    <div class="content-home">
        <div class="page-1 page">
            <div class="hero">
                <div class="chips">
                    <p>Recommendation System</p>
                </div>
                <div class="hero-content">
                    <p>Discovering your <b>future career</b> path.</p>
                    <p>An assessment system based on NCAE-aligned aptitude evaluation designed to transform assessment
                        data into a clear narrative of professional trajectory.</p>
                    <div class="cta-btn">
                        <a href="{{ url("/assessment-entry") }}" class="hero-btn-action">
                            <span>Start Exam</span>
                            <i class="btn-icon icon-arrow-right"></i>
                        </a>
                        <a href="{{ url("/retrieve-result") }}" class="hero-btn-action">
                            <!-- <i class="btn-icon icon-search-status" alt="Search Icon"></i> -->
                            <span>See Results</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="right-bar-chart">

                <div class="left-chart card">
                    <div class="chart-label">
                        <h3>Core Competencies</h3>
                        <i class="icon-chart" alt="Chart Icon"></i>
                    </div>

                    <div class="chart-bar" style="position: relative;">
                        <!-- LEFT: Labels -->
                        <div id="custom-labels">
                        </div>

                        <!-- RIGHT: Chart -->
                        <div class="canvas-wrapper">
                            <canvas id="bar-chart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="chip">
                    <p class="label">Sample Output (Model-Based Prediction)</p>
                </div>

            </div>
        </div>

        <div class="page-2 page">
            <div class="left-content" style="grid-area: box-1;">
                <p>Track Discovery</p>
                <p>Move beyond standard lists. Explore career clusters mapped to your psychological profile and
                    technical aptitude.</p>
            </div>
            <div class="right-content" style="grid-area: box-2;">
                <div class="r1">
                    <p>Top Tracks</p>
                    @foreach ($topTracks as $track)
                        <p>{{ $track['track'] }} - {{ $track['percentage'] }}</p>
                    @endforeach
                </div>
                <div class="r2">
                    <p>Assessment Areas</p>
                    <p>Logical-Mathematical Reasoning</p>
                    <p>Syntax & Structure Analysis</p>
                    <p>Systems Hardware & Networking</p>
                    <p>Digital Aesthetics & UI Design</p>
                </div>
            </div>

            <div class="bottom-content" style="grid-area: box-3;">
                <div class="left" style="grid-area: box-1;">
                    <p>Career Aptitude Matrix</p>
                    <p>This system analyzes student responses and performance metrics using a Random Forest
                        classification model to generate career track recommendations.</p>
                </div>
                <div class="right" style="grid-area: box-2;">
                    <div class="img-container">
                        <img class="matrix-img" src="{{ asset('assets/images/data_matrix.png') }}"
                            alt="Career Aptitude Matrix" srcset="">
                        <img class="overlay" src="{{ asset('assets/images/College_of_Informatics_72_R.png') }}"
                            alt="Career Aptitude Matrix" srcset="">
                    </div>
                </div>
            </div>
        </div>

        <div class="page-3">
            <div class="top-content" style="grid-area: box-1;">
                <p>Start Your Assessment</p>
            </div>
            <div class="left action-container" style="grid-area: box-2;">
                <p>Aptitude Evaluation</p>
                <p>Complete the 45-minute core assessment to unlock your basic career track suggestions.</p>
                <a href="{{ url("/assessment-entry") }}" class="p3-btn-action">
                    <span>Start Exam</span>
                    <!-- <i class="btn-icon icon-arrow-right"></i> -->
                </a>
            </div>
            <div class="right action-container" style="grid-area: box-3;">
                <p>Result Synthesis</p>
                <p>Review your assessment results and gain insights into your career preferences and strengths.</p>
                <a href="{{ url("/retrieve-result") }}" class="p3-btn-action">
                    <!-- <i class="btn-icon icon-search-status" alt="Search Icon"></i> -->
                    <span>See Results</span>
                </a>
            </div>

            <div class="info-cards" style="grid-area: box-4;">
                <i class="icon icon-think"></i>
                <h3>How it works</h3>
                <p>1. Complete the Assessment <br> 2. System Analyzes Responses <br> 3. Receive Career Track
                    Recommendations</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>© 2026 Philippine Christian University - College of Informatics. All rights reserved.</p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>
    <script src="{{ asset('assets/js/flash_message.js') }}"></script>


    <script>
        const trackPercentage = {
            "Computer Engineering": { percentage: 26.5 },
            "Computer Science": { percentage: 28.2 },
            "Information Technology": { percentage: 34.8 },
            "Multimedia Arts": { percentage: 10.5 }
        };

        const coreCompetencies = {
            "Logical-Mathematical Reasoning": {
                score: 0.82,
                level: "Level: Advanced"
            },
            "Syntax & Structure Analysis": {
                score: 0.65,
                level: "Level: Intermediate"
            },
            "Systems Hardware & Networking": {
                score: 0.58,
                level: "Level: Intermediate"
            },
            "Digital Aesthetics & UI Design": {
                score: 0.40,
                level: "Level: Beginner"
            }
        };

        const detailedCompetencies = {
            logical_reasoning: { score: 0.85 },
            algorithmic_thinking: { score: 0.80 },
            problem_solving: { score: 0.82 },

            syntax_analysis: { score: 0.65 },

            hardware_systems: { score: 0.60 },
            networking_systems: { score: 0.55 },
            system_organization: { score: 0.58 },

            ui_design: { score: 0.45 },
            digital_creativity: { score: 0.35 }
        };
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initCharts(trackPercentage, detailedCompetencies, coreCompetencies);
        });
    </script>

    <script src="{{ asset('assets/js/charts.js') }}"></script>
</body>

</html>