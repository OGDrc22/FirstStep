
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCU First Step</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/welcome.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/flash_message.css')}}">
</head>

<body>

    @if (session('error'))
        <div class="alert alert-danger">
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

    <div class="header-pcu">
        <img class="pcu-logo" src="{{ asset('assets/images/main_logo.png') }}" alt="" srcset="">
        <a class="pcu-text">PCU - COI First Step</a>
        <img class="pcu-coi-logo" src="{{ asset('assets/images/College_of_Informatics_72_R.png') }}" alt="" srcset="">
    </div>

    <div class="container-btn">
        <a href="{{ url("/assessment-entry") }}" class="btn-start-exam">Start Exam</a>
        <a href="{{ url("/retrieve-result") }}" class="btn-retrieve-result">Retrieve Result</a>
    </div>

    <script src="{{ asset('assets/js/flash_message.js') }}"></script>
</body>
</html>