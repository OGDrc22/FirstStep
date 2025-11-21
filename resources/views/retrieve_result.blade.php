<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrieve Result</title>
</head>
<body>
    Result Page
    
    @if (!isset($sampleResult))
        <div class="login-container">
            <h2>Login</h2>
            <form method="POST" action="/get-result">
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
                    <label for="token">Token:</label>
                    <input type="text" id="token" name="token" >
                </div>
                <button type="submit" class="btn-start">Start</button>
            </form>
        </div>
    @endif

        @if (isset($sampleResult))
            <h3>Result for {{ $sampleResult['name'] }}<h3>
            <p>Score: {{ $sampleResult['score'] }}</p>
            <p>Details: {{ $sampleResult['recommendation'] }}</p>
        @else
            <p>No result to display.</p>
        @endif
</body>
</html>