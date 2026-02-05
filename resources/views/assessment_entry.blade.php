<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assesment Entry</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/assessment_entry.css')}}">
</head>

<body>

    <div class="loading-screen" id="loading-screen">
        <div class="loader-container">
            <!-- <div class="spinner"></div> -->
            <span class="loader"></span>
            <p id="text-loader">{{ session('status') }}</p>

        </div>
    </div>



    <div class="login-container">
        <form method="POST" action="" id="assessment-form">
            @csrf

            <h2>Assesment Entry</h2>

            <!-- Progress bar -->

            <div class="progressbar">

                <div class="progress" id="progress"></div>

                <div class="progress-step active" data-title="Basic Info"></div>
                <div class="progress-step" data-title="Interest"></div>
                <div class="progress-step" data-title="Self Skill Rating"></div>
                <div class="progress-step" data-title="Mini Test"></div>
            </div>

            
            <input type="hidden" name="minitest" id="minitest-input">

            <!-- Steps -->
            <div class="forms-data-collection">
                <div class="form-step active">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email">
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn next-btn ml-50">Next</button>
                    </div>
                </div>

                <!-- Interest Step -->
                <div class="form-step">
                    <div class="form-group">
                        <label for="student_id">Interest:</label>
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
                                    <img src="" alt="" class="interest-img">
                                    <h3 data-value="software_mobile_dev">Software/Mobile App Development</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="" alt="" class="interest-img">
                                    <h3 data-value="cybersec_hacking">Cybersecurity/Hacking</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="" alt="" class="interest-img">
                                    <h3 data-value="networking">Networking</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="" alt="" class="interest-img">
                                    <h3 data-value="building_robots">Building Robots</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="" alt="" class="interest-img">
                                    <h3 data-value="data_analytics">Data Analytics</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="" alt="" class="interest-img">
                                    <h3 data-value="ui_ux_designer">UX/UI Design</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="" alt="" class="interest-img">
                                    <h3 data-value="videographer">Videographer</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="" alt="" class="interest-img">
                                    <h3 data-value="editor">Editor</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="" alt="" class="interest-img">
                                    <h3 data-value="graphic_design">Graphic Design</h3>
                                </div>
                                <div class="interest-card">
                                    <img src="" alt="" class="interest-img">
                                    <h3 data-value="ai_ml">Artificial Intelligence / Machine Learning</h3>
                                </div>

                                <input type="hidden" name="interest" id="interest-input">
                            </div>
                            <div class="interest-other-list">
                                <ul id="other-interest">
                                </ul>
                            </div>
                            <div class="interest-other">
                                <label for="other-interest"></label>
                                <input type="text" name="" id="other-interest-input" placeholder="Other">
                                <button class="add-other-interest">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn prev-btn">Previous</button>
                        <button type="button" class="btn next-btn" id="interest-next-btn">Next</button>
                    </div>
                </div>

                <!-- Skill Test -->
                <div class="form-step" id="diagnostic-skill-test">
                    <!-- <div class="labels">
                        <div class="skill-text">

                        </div>
                        <div class="scale-text">
                            <label for="">Novice</label>
                            <label for="">Beginner</label>
                            <label for="">Intermediate</label>
                            <label for="">Advance</label>
                            <label for="">Expert</label>
                        </div>
                    </div> -->
                    <div class="form-container" id="likert-container">

                    </div>
                    <div class="buttons">
                        <button type="button" class="btn prev-btn">Previous</button>
                        <button type="button" class="btn next-btn" id="skill-next-btn">Next</button>
                    </div>
                </div>

                <!-- Mini Test -->
                <div class="form-step" id="diagnostic-mini-test">
                    <div class="form-container">
                        <h3>Mini Test</h3>
                        <div class="mini-test-container" id="mini-test-container">
                        </div>
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn prev-btn">Previous</button>
                        <button type="submit" class="btn next-btn">Submit</button>
                    </div>
                </div>


            </div>
        </form>
        <!-- <button type="submit" class="btn-start" id="start-btn">Start</button> -->
    </div>

    <a href="{{ route('welcome') }}">Home</a>


    <script src="{{ asset('assets/js/assesment_entry.js') }}"></script>
</body>

</html>