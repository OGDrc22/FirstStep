<!DOCTYPE html>
<html>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background:#f4f6f9;">

<div style="max-width:600px; margin:auto; background:#ffffff; padding:20px;">

    <!-- HEADER -->
    <div style="text-align:center; margin-bottom:20px;">
        <h2 style="margin:0; color:#191b1f;">Assessment History & Results</h2>
        <p style="color:#777; font-size:14px; margin-top:0px;">
            A summary of your academic trajectory and performance over time.
        </p>
    </div>

    <!-- USER INFO -->
    <div style="margin-bottom:20px;">
        <p><strong>Name:</strong> {{ $results['username'] }}</p>
        <p><strong>Email:</strong> {{ $results['useremail'] }}</p>
    </div>

    <!-- RECOMMENDATION -->
    <div style="background:hsla(216, 100%, 85%, 0.5); padding:15px; border-radius:8px; margin-bottom:20px;">
        <h5 style="margin:0; color:#595f69;">Primary Recommendation</h5>
        <h3 style="margin:5px 0; color:hsl(211, 73%, 48%);">{{ $results['recommendedTrack'] }}</h3>
        <p>{{ $results['note'] }}</p>
    </div>

    <div style="background:hsla(210, 10%, 88%, 0.5); padding:15px; border-radius:8px; margin-bottom:20px;">
        <h5 style="margin:0; color:#595f69;">Secondary Recommendation</h5>
        <h3 style="margin:5px 0;">{{ $results['secondaryTrack'] }}</h3>
    </div>

    <!-- TRACK BREAKDOWN (REPLACED CHART) -->
    <div style="margin-bottom:20px;">
        <h3 style="margin-bottom: 4px;">Track Breakdown</h3>
        <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse;">
            <thead>
                <tr style="background:#eeeeee;">
                    <th align="left">Track</th>
                    <th align="right">Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results['computedTrackPercentage'] as $comp => $c)
                    <tr style="width: 100%;">
                        <td align="left">{{ $comp }}</td>
                        <td align="right">{{ number_format($c, 2)}}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- HISTORY TABLE -->
    <div style="margin-top:20px;">
        <h3 style="margin-bottom: 4px;">Previous Assessment Attempts</h3>

        <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse;">
            <thead>
                <tr style="background:#eeeeee;">
                    <th align="left">Date</th>
                    <th align="left">Predicted Track</th>
                    <th align="left">Secondary</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results['examResult'] as $exam)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($exam['created_at'])->format('M d, Y h:i') }}</td>
                    <td>{{ $exam['predicted_track']['track'] }}</td>
                    <td>{{ $exam['secondary_track']['track'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- INFO SECTION -->
    <div style="margin-top:30px;">
        <h3>How Your Results Are Calculated</h3>

        <ul style="padding-left:20px; color:#333;">
            <li><strong>Aptitude Tests:</strong> Problem-solving and logic ability.</li>
            <li><strong>Core Competencies:</strong> Technical skill evaluation.</li>
            <li><strong>Career Interest:</strong> Preference profiling.</li>
            <li><strong>Weighted Model:</strong>
                <br>• 60% User Performance  
                <br>• 40% Machine Learning Prediction
            </li>
        </ul>
    </div>

    <!-- FOOTER -->
    <div style="margin-top:30px; text-align:center; font-size:12px; color:#999;">
        <p>© 2026 Philippine Christian University - College of Informatics. All rights reserved.</p>
        <p>This is an automated email. Please do not reply.</p>
    </div>

</div>

</body>
</html>