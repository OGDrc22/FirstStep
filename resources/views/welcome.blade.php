
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
        <div class="alert heads-up-message hum-error">
            <p class="p p-danger">{{ session('error') }}</p>
        </div>
    @endif


    <!-- Sample Modal Messages -->
    <!-- <div class="alert heads-up-message hum-error">
        <p class="p p-danger">Sample Error Message</p>
    </div> -->

    <!-- <div class="alert-bg">
        <div class="alert">
            <div class="simple-flash-message">
                <span class="icon-danger"></span>
                <h2>Sample Error Message</h2>
                <div class="alert-button-container">
                    <button class="btn btn-secondary">Cancel</button>
                    <button class="btn btn-primary">OK</button>
                </div>
            </div>
        </div>
    </div> -->
    <!-- <div class="alert-bg">
        <div class="alert">
            <div class="alert-form">
                <p>Feedback Form</p>
                <textarea type="text" class="feedback-input" name="feedback" id="feedback" placeholder="Enter your feedback here..."></textarea>
                <div class="alert-button-container">
                    <button class="btn btn-secondary"><span class="icon-arrow-right"></span> Cancel</button>
                    <button class="btn btn-middle">Skip <span class="icon-arrow-right"></span></button>
                    <button class="btn btn-primary">Submit <span class="icon-send"></span></button>
                </div>
            </div>
        </div>
    </div> -->
    <!-- End of Sample Modal Messages -->


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