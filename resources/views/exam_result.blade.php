<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>
</head>
<body>
    Result Page
    @if (isset($resData))
        <h3>Score: {{ $resData['score'] }}</h3>
        <h3>Total: {{ $resData['total'] }}</h3>
    @endif
</body>
</html>