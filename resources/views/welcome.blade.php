
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCU First Step</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/welcome.css')}}">
</head>

<body>

    <div class="header-pcu">
        <img class="pcu-logo" src="{{ asset('assets/images/main_logo.png') }}" alt="" srcset="">
        <a class="pcu-text">PCU - COI First Step</a>
        <img class="pcu-coi-logo" src="{{ asset('assets/images/College_of_Informatics_72_R.png') }}" alt="" srcset="">
    </div>

    <div class="container-btn">
        <a href="{{ url("/login") }}" class="btn-start-exam">Start Exam</a>
        <a href="{{ url("/retrieve-result") }}" class="btn-retrieve-result">Retrieve Result</a>
    </div>
</body>
</html>