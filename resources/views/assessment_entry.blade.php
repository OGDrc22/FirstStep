<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assesment Entry</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/flash_message.css')}}?v={{ time() }}">
    <link rel="stylesheet" href="{{asset('assets/css/nav_bar.css')}}?v={{ time() }}">
    <link rel="stylesheet" href="{{asset('assets/css/assessment_entry.css')}}?v={{ time() }}">
</head>

<body class="bg-overlay">

    <div class="loading-screen" id="loading-screen">
        <div class="loader-container">
            <!-- <div class="spinner"></div> -->
            <span class="loader"></span>
            <p id="text-loader">{{ session('status') }}</p>

        </div>
    </div>

    <nav class="top-nav-pcu">
        <a class="top-nav-left" href="{{ route('welcome') }}">
            <img class="pcu-logo" src="{{ asset('assets/images/main_logo.png') }}" alt="PCU Logo">
            <span class="pcu-text-small">Philippine Christian University</span>
        </a>
        <a class="top-nav-right" href="{{ route('welcome') }}">
            <span class="coi-text">COI First Step</span>
            <img class="pcu-coi-logo" src="{{ asset('assets/images/College_of_Informatics_72_R.png') }}"
                alt="COI Logo">
        </a>
    </nav>



    <div class="container">
        <div class="assessment-card">

            <form method="POST" action="" id="assessment-form">
                @csrf

                <div class="assessment-top">
                    <div class="header">
                        <h3></h3>
                        <p></p>
                    </div>

                    <div class="progressbar">
                        <!-- <div class="progress" id="progress"></div> -->
                        <div class="progress-step active" data-title="Basic Info"></div>
                        <div class="progress-step" data-title="Interest"></div>
                        <div class="progress-step" data-title="Self Skill Rating"></div>
                        <div class="progress-step" data-title="Mini Test"></div>
                    </div>
                </div>

                <input type="hidden" name="minitest_json" id="minitest-input">

                <div class="forms-data-collection">

                    <div class="form-step active" id="step-1" data-title="Foundation of Growth"
                        data-subtitle="Begin your career intelligence journey. Your basic details allow us to contextualize your assessment results within global industry standards.">

                        <div class="input-stacked-container">
                            <div class="email-input input-container">
                                <label for="name">Username</label>
                                <input type="text" id="name" name="name" placeholder="Userame" class="pcu-field"
                                    required>
                            </div>

                            <div class="email-input input-container">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" placeholder="Email@gmail.com"
                                    class="pcu-field" required>
                            </div>
                        </div>

                        <div class="buttons">
                            <p><i class="icon icon-lock"></i>Encrypted Data Secure</p>
                            <button type="button" class="btn next-btn" id="basic-info-next-btn">Next <i
                                    class="icon icon-arrow-right"></i></button>
                        </div>
                    </div>

                    <div class="form-step" id="step-2" data-title="What Sparks Your Curiosity?"
                        data-subtitle="Select the areas of technology and design that resonate with your professional aspirations. This helps us curate your personalized career path.">
                        <label style="display: none;" for="student_id">Interest:</label>

                        <div class="interest-selection">
                            <div class="interest-options">
                                <div class="interest-card">
                                    <div class="icon-container">
                                        <i class="icon icon-coding" alt="Coding Icon"></i>
                                    </div>
                                    <h3 data-value="coding">Coding</h3>
                                    <p>Mastering languages like Python, Java, and C++ to build robust software
                                        architectures.</p>
                                </div>
                                <div class="interest-card">
                                    <div class="icon-container">
                                        <i class="icon icon-game-dev" alt="Game Development Icon"></i>
                                    </div>
                                    <h3 data-value="game_development">Game Development</h3>
                                    <p>Designing immersive worlds and mechanics using Unity, Unreal Engine, and C#.
                                    </p>
                                </div>
                                <div class="interest-card">
                                    <div class="icon-container">
                                        <i class="icon icon-software" alt="Software Development Icon"></i>
                                    </div>
                                    <h3 data-value="software_mobile_dev">Software/Mobile App Development</h3>
                                    <p>Creating seamless user experiences for iOS and Android with Swift and Kotlin.
                                    </p>
                                </div>
                                <div class="interest-card">
                                    <div class="icon-container">
                                        <i class="icon icon-security" alt="Cybersecurity Icon"></i>
                                    </div>
                                    <h3 data-value="cybersec_hacking">Cybersecurity/Hacking</h3>
                                    <p>Protecting systems, networks, and programs from digital attacks and ensuring
                                        data integrity.</p>
                                </div>
                                <div class="interest-card">
                                    <div class="icon-container">
                                        <i class="icon icon-networking" alt="Networking Icon"></i>
                                    </div>
                                    <h3 data-value="networking">Networking</h3>
                                    <p>Designing and maintaining computer networks to ensure efficient data
                                        communication.</p>
                                </div>
                                <div class="interest-card">
                                    <div class="icon-container">
                                        <i class="icon icon-bot" alt="Bot Icon"></i>
                                    </div>
                                    <h3 data-value="building_robots">Building Robots</h3>
                                    <p>Integrating mechanical engineering with smart software to automate complex
                                        tasks.</p>
                                </div>
                                <div class="interest-card">
                                    <div class="icon-container">
                                        <i class="icon icon-chart" alt="Data Analytics Icon"></i>
                                    </div>
                                    <h3 data-value="data_analytics">Data Analytics</h3>
                                    <p>Interpreting complex data sets to drive business decisions and uncover
                                        valuable insights.</p>
                                </div>
                                <div class="interest-card">
                                    <div class="icon-container">
                                        <i class="icon icon-ux-ui" alt="UX/UI Design Icon"></i>
                                    </div>
                                    <h3 data-value="ui_ux_designer">UX/UI Design</h3>
                                    <p>Creating intuitive and engaging user experiences through thoughtful design
                                        principles.</p>
                                </div>
                                <div class="interest-card">
                                    <div class="icon-container">
                                        <i class="icon icon-vidography" alt="Videography Icon"></i>
                                    </div>
                                    <h3 data-value="videographer">Videographer</h3>
                                    <p>Capturing compelling visual stories through expert cinematography and
                                        editing.</p>
                                </div>
                                <div class="interest-card">
                                    <div class="icon-container">
                                        <i class="icon icon-video-editing" alt="Video Editing Icon"></i>
                                    </div>
                                    <h3 data-value="editor">Editor</h3>
                                    <p>Editing and post-production work to create polished final products.</p>
                                </div>
                                <div class="interest-card">
                                    <div class="icon-container">
                                        <i class="icon icon-graphic-design" alt="Graphic Design Icon"></i>
                                    </div>
                                    <h3 data-value="graphic_design">Graphic Design</h3>
                                    <p>Creating visually appealing designs for various media and platforms.</p>
                                </div>
                                <div class="interest-card">
                                    <div class="icon-container">
                                        <i class="icon icon-bot" alt="Coding Icon"></i>
                                    </div>
                                    <h3 data-value="ai_ml">Artificial Intelligence / Machine Learning</h3>
                                    <p>Understanding and applying AI and ML techniques to solve complex problems.
                                    </p>
                                </div>

                                <input type="hidden" name="interest" id="interest-input">
                            </div>

                            <div class="interest-other-list">
                                <div class="other-interest" id="other-interest"></div>
                            </div>

                            <div class="input-container">
                                <label for="other-interest-input">Other Interest</label>
                                <input type="text" id="other-interest-input" placeholder="Other Interest">
                            </div>
                        </div>


                        <div class="buttons">
                            <button type="button" class="btn prev-btn"><i class="icon icon-arrow-left"></i>
                                Previous</button>
                            <button type="button" class="btn next-btn" id="interest-next-btn">Next <i
                                    class="icon icon-arrow-right"></i></button>
                        </div>
                    </div>


                    <div class="form-step" id="diagnostic-skill-test" data-title="Rate your self"
                        data-subtitle="Evaluate your proficiency across key technical and analytical domains. Your honest self-assessment helps our engine map your potential to the most relevant career trajectories.">
                        <div>
                            <div class="form-container" id="likert-container" style="width: 100%;">

                            </div>

                            <div class="buttons">
                                <button type="button" class="btn prev-btn"><i class="icon icon-arrow-left"></i>
                                    Previous</button>
                                <button type="button" class="btn next-btn" id="skill-next-btn">Next <i
                                        class="icon icon-arrow-right"></i></button>
                            </div>
                        </div>

                        <div class="info-cards">
                            <div class="card">
                                <i class="icon icon-think"></i>
                                <h3>Why Self-Rating Matters</h3>
                                <p>Research shows that self-reflection activates neural pathways associated with
                                    meta- cognition, allowing for more accurate skill alignment in vocational
                                    transitions.</p>
                            </div>
                            <div class="card">
                                <i class="icon icon-book"></i>
                                <h3>How Your Responses Are Used</h3>
                                <p>Your self-assessment is combined with test performance and interest data. These
                                    inputs are processed using a machine learning model to generate career track
                                    recommendations</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-step" id="diagnostic-mini-test" data-title="Mini Test"
                        data-subtitle="Evaluate your proficiency across key technical and analytical domains. Your honest self-This final evaluation assesses your foundational knowledge and technical aptitude within your selected areas of interest.">
                        <div class="form-container">
                            <div class="inf">
                                <h3>Mini Test</h3>
                                <h3 id="timer-display">Submit in: <span id="seconds">0</span>s</h3>
                            </div>
                            <div class="mini-test-container" id="mini-test-container">
                            </div>
                        </div>
                        <div class="buttons">
                            <button type="button" class="btn prev-btn"><i class="icon icon-arrow-left"></i>
                                Previous</button>
                            <button type="submit" class="btn next-btn" id="submit-btn">Submit <i
                                    class="icon icon-arrow-right"></i></button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="footer">
        <p>© 2026 Philippine Christian University - College of Informatics. All rights reserved.</p>
    </div>


    <script src="{{ asset('assets/js/assesment_entry.js') }}"></script>
    <script type="module" src="{{ asset('assets/js/flash_message.js') }}"></script>
</body>

</html>