<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>

    <div>
        <h1>Thank You for Registering!</h1>

        <p>Please use the following verification code to verify your email:</p>

        <p style="color: black;">
            Verification Code: {{ $verificationCode }}
        </p>

        <p>Thanks,<br>{{ config('app.name') }}</p>
    </div>

</body>
</html>
