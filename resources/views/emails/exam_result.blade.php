<!DOCTYPE html>
<html>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background:#f4f6f9;">

<div style="max-width:600px; margin:auto; background:#ffffff; padding:20px;">

    <!-- HEADER -->
    <div style="text-align:center; margin-bottom:20px;">
        <h2 style="margin:0; color:#333;">Assessment Result</h2>
        <p style="margin:5px 0; color:#777;">COI First Step</p>
    </div>

    <!-- USER INFO -->
    <div style="margin-bottom:20px;">
        <p style="margin:5px 0;"><strong>Name:</strong> {{ $results['username'] }}</p>
        <p style="margin:5px 0;"><strong>Email:</strong> {{ $results['useremail'] }}</p>
    </div>

    <!-- PRIMARY RESULT -->
    <div style="margin-bottom:20px; padding:15px; background:#f9fafb; border-radius:8px;">
        <p style="margin:0; font-size:14px; color:#555;">Primary Recommendation</p>
        <h3 style="margin:5px 0; color:#4CAF50;">
            {{ $results['predictedTrack']['track'] }}
        </h3>
        <p style="margin-top:10px; color:#333;">
            {{ $results['note'] }}
        </p>
    </div>

    <!-- SECONDARY -->
    <div style="margin-bottom:20px; padding:15px; background:#f9fafb; border-radius:8px;">
        <p style="margin:0; font-size:14px; color:#555;">Secondary Recommendation</p>
        <h3 style="margin:5px 0; color:#333;">
            {{ $results['secondaryTrack']['track'] }}
        </h3>
    </div>

    <!-- APTITUDE -->
    <div style="text-align:center; margin:20px 0;">
        <h1 style="margin:0; color:#333;">{{ $results['aptitude'] }}%</h1>
        <p style="margin:0; color:#777;">APTITUDE SCORE</p>
    </div>

    <!-- TRACK BREAKDOWN (TABLE INSTEAD OF CHART) -->
    <div style="margin-top:20px;">
        <h3 style="color:#333;">Track Breakdown</h3>

        <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse;">
            <thead>
                <tr style="background:#eeeeee;">
                    <th align="left">Track</th>
                    <th align="left">Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results['trackPercentage'] as $track)
                <tr>
                    <td>{{ $track['track'] }}</td>
                    <td>{{ $track['percentage'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div style="margin-top:30px; font-size:12px; color:#999; text-align:center;">
        <p>This is an automated email. Please do not reply.</p>
    </div>

</div>

</body>
</html>