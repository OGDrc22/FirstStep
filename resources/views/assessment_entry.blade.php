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
            <div class="spinner"></div>
            @if (session('status'))
            @else
            @endif
            
            <p id="text-loader">{{ session('status') }}</p>
        </div>
    </div>

    <div class="login-container">
        <h2>Assesment Entry</h2>
        <form method="POST" action="" id="assessment-form">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" > 
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" >
            </div>
            <div class="form-group">
                <h3 class="">Gender:</h3>
                <div class="rad">
                    <input type="radio" id="male" name="gender" value="male">
                    <label for="male">Male</label>
                    <input type="radio" id="female" name="gender" value="female">
                    <label for="female">Female</label>
                </div>
            </div>
            <div class="form-group">
                <label for="country_name_input">Country:</label>
                <input list="country_list" id="country_name_imput" name="country_name" >
                <datalist id="country_list">
                    <option value="Philippines">
                    <option value="United States">
                    <option value="Canada">
                    <option value="United Kingdom">
                    <option value="Australia">
                    <option value="Germany">
                    <option value="France">
                    <option value="India">
                    <option value="China">
                    <option value="Japan">
                    <option value="South Korea">
                    <option value="Brazil">
                    <option value="Mexico">
                    <option value="Italy">
                    <option value="Spain">
                    <option value="Russia">
                    <option value="Netherlands">
                    <option value="Sweden">
                    <option value="Switzerland">
                    <option value="Belgium">
                    <option value="Argentina">
                    <option value="South Africa">
                    <option value="New Zealand">
                    <option value="Singapore">
                    <option value="Malaysia">
                    <option value="Thailand">
                    <option value="Vietnam">
                    <option value="Indonesia">
                    <option value="Turkey">
                    <option value="Saudi Arabia">
                    <option value="United Arab Emirates">
                    <option value="Egypt">
                    <option value="Nigeria">
                    <option value="Kenya">
                    <option value="Ghana">
                    <option value="Colombia">
                    <option value="Chile">
                </datalist>
            </div>
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
                            <h3 data-value="sofware_mobile_dev">Software/Mobile App Development</h3>
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
                            <h3 data-value="general">General</h3>
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
            <div id="btn-dummy" type="dummy">Try</div>
            <button type="submit" class="btn-start" id="start-btn">Start</button>
        </form>
    </div>
    
    <a href="{{ route('welcome') }}">Home</a>


    <script src="{{ asset('assets/js/assesment_entry.js') }}"></script>
</body>
</html>