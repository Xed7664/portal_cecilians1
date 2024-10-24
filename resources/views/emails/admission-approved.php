<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Approved</title>
</head>
<body>
    <h1>Congratulations, {{ $admission->full_name }}!</h1>
    <p>Your admission has been approved. Your Student ID is: <strong>{{ $studentID }}</strong>.</p>
    <p>You can now log in to the portal using your Student ID.</p>
    <p>Thank you for joining us!</p>
</body>
</html>
