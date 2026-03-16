<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assesment Entry</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/flash_message.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/assessment_entry.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/nav_bar.css')}}">
</head>

<body class="bg-overlay">

    <div class="loading-screen" id="loading-screen">
        <div class="loader-container">
            <!-- <div class="spinner"></div> -->
            <span class="loader"></span>
            <p id="text-loader">{{ session('status') }}</p>

        </div>
    </div>

    <div class="alert heads-up-message hum-error">
        <p class="p p-danger">Sample Error Message</p>
    </div>

    <header class="header-pcu">
        <div class="header-left">
            <a class="pcu-home-link" href="{{ route('welcome') }}">
                <img class="pcu-logo" src="{{ asset('assets/images/main_logo.png') }}" alt="PCU Logo">
                <span class="pcu-text-small">Philippine Christian University</span>
            </a>
        </div>
        <div class="header-right">
            <span class="coi-text">COI First Step</span>
            <img class="pcu-coi-logo" src="{{ asset('assets/images/College_of_Informatics_72_R.png') }}" alt="COI Logo">
        </div>
    </header>


    <div class="login-container">

        <form method="POST" action="" id="assessment-form">
            @csrf

            <h2>Assessment Entry</h2>

            <div class="progressbar">
                <div class="progress" id="progress"></div>
                <div class="progress-step active" data-title="Basic Info"></div>
                <div class="progress-step" data-title="Interest"></div>
                <div class="progress-step" data-title="Self Skill Rating"></div>
                <div class="progress-step" data-title="Mini Test"></div>
            </div>

            <input type="hidden" name="minitest_json" id="minitest-input">

            <div class="forms-data-collection">

                <div class="form-step active" id="step-1">
                    <div class="input-stacked-container">
                        <input type="text" id="name" name="name" placeholder="Name" class="pcu-field" required>
                        <input type="email" id="email" name="email" placeholder="Email@gmail.com" class="pcu-field" required>
                    </div>

                    <div class="buttons" style="justify-content: flex-end;">
                        <button type="button" class="btn next-btn" id="basic-info-next-btn">Next <i class="icon-arrow-right"></i></button>
                    </div>
                </div>

                <div class="form-step" id="step-2">
                    <div class="form-group">
                        <label style="display: none;" for="student_id">Interest:</label>

                        <div class="interest-selection">
                            <div class="interest-options">
                                <div class="interest-card">
                                    <img src="{{ asset('assets/images/coding.png') }}" alt="" class="interest-img">
                                    <h3 data-value="coding">Coding</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="{{ asset('assets/images/gamedev.png') }}" alt="" class="interest-img">
                                    <h3 data-value="game_development">Game Development</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="{{ asset('assets/images/mobile_app.png') }}" alt="" class="interest-img">
                                    <h3 data-value="software_mobile_dev">Software/Mobile App Development</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="{{ asset('assets/images/hack.png') }}" alt="" class="interest-img">
                                    <h3 data-value="cybersec_hacking">Cybersecurity/Hacking</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="{{ asset('assets/images/network.png') }}" alt="" class="interest-img">
                                    <h3 data-value="networking">Networking</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="{{ asset('assets/images/building_robot.png') }}" alt=""
                                        class="interest-img">
                                    <h3 data-value="building_robots">Building Robots</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="{{ asset('assets/images/data_analytics.png') }}" alt=""
                                        class="interest-img">
                                    <h3 data-value="data_analytics">Data Analytics</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="{{ asset('assets/images/ui_design.png') }}" alt="" class="interest-img">
                                    <h3 data-value="ui_ux_designer">UX/UI Design</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="{{ asset('assets/images/videographer.png') }}" alt=""
                                        class="interest-img">
                                    <h3 data-value="videographer">Videographer</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="{{ asset('assets/images/editor.png') }}" alt="" class="interest-img">
                                    <h3 data-value="editor">Editor</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="{{ asset('assets/images/graphic_design.png') }}" alt=""
                                        class="interest-img">
                                    <h3 data-value="graphic_design">Graphic Design</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="{{ asset('assets/images/ai.png') }}" alt="" class="interest-img">
                                    <h3 data-value="ai_ml">Artificial Intelligence / Machine Learning</h3>
                                </div>

                                <input type="hidden" name="interest" id="interest-input">
                            </div>

                            <div class="interest-other-list">
                                <ul id="other-interest"></ul>
                            </div>

                            <div class="interest-other-container">
                                <input type="text" id="other-interest-input" placeholder="Other Interest">
                                <button type="button" id="add-other" class="add-other-interest">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="buttons">
                        <button type="button" class="btn prev-btn"><i class="icon-arrow-left"></i> Previous</button>
                        <button type="button" class="btn next-btn" id="interest-next-btn">Next <i class="icon-arrow-right"></i></button>
                    </div>
                </div>

                <div class="form-step" id="diagnostic-skill-test">
                    <div class="form-container" id="likert-container" style="width: 100%;">

                        

                    </div>

                    <div class="buttons">
                        <button type="button" class="btn prev-btn"><i class="icon-arrow-left"></i> Previous</button>
                        <button type="button" class="btn next-btn" id="skill-next-btn">Next <i class="icon-arrow-right"></i></button>
                    </div>
                </div>
                <div class="form-step" id="diagnostic-mini-test">
                    <div class="form-container">
                        <div class="inf">
                            <h3>Mini Test</h3>
                            <h3 id="timer-display">Submit in: <span id="seconds">0</span>s</h3>
                        </div>
                        <div class="mini-test-container" id="mini-test-container">
                        </div>
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn prev-btn"><i class="icon-arrow-left"></i> Previous</button>
                        <button type="submit" class="btn next-btn">Submit <i class="icon-arrow-right"></i></button>
                    </div>
                </div>

            </div>
        </form>
    </div> 
    <div class="home-btn-container">
        <a href="{{ route('welcome') }}" class="home-link"><i class="icon-home"></i> Back to Home</a>
    </div>

    <script src="{{ asset('assets/js/assesment_entry.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/flash_message.js') }}"></script> -->
</body>

</html>