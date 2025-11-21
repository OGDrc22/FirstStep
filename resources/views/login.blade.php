<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="{{asset('assets/images/main_logo.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/welcome.css')}}">
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="/login-data">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" > 
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" >
            </div>
            <button type="submit" class="btn-start">Start</button>
        </form>
    </div>
</body>
</html>