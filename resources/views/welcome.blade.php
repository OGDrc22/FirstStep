
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCU First Step</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/welcome.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/nav_bar.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/flash_message.css')}}">
</head>

<body class="bg-overlay">

    @if (session('error'))
        <div class="heads-up-message">
            {{ session('error') }}
        </div>
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

    
<header class="header-pcu">
        <div class="header-left">
            <img class="pcu-logo" src="{{ asset('assets/images/main_logo.png') }}" alt="PCU Logo">
            <span class="pcu-text-small">Philippine Christian University</span>
        </div>
        <div class="header-right">
            <span class="coi-text">COI First Step</span>
            <img class="pcu-coi-logo" src="{{ asset('assets/images/College_of_Informatics_72_R.png') }}" alt="COI Logo">
        </div>
    </header>

<div class="page-center-container">
        <div class="action-container-wrapper">
            <div class="container-btn">
                <a href="{{ url("/assessment-entry") }}" class="btn-action">
                    <i class="btn-icon icon-book"></i>
                    <span>Start Exam</span>
                </a>
                <a href="{{ url("/retrieve-result") }}" class="btn-action">
                    <i class="btn-icon icon-search-status" alt="Search Icon"></i>
                    <span>See Results</span>
                </a>
            </div>
        </div>
</div>

    <script src="{{ asset('assets/js/flash_message.js') }}"></script>
</body>
</html>
