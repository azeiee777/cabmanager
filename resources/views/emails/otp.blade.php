<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            background-color: #0a0a0a;
            color: #ffffff;
            padding: 20px;
        }

        .card {
            background-color: #171717;
            padding: 30px;
            border-radius: 20px;
            border: 1px solid #262626;
            text-align: center;
        }

        .code {
            font-size: 32px;
            font-weight: 900;
            color: #f59e0b;
            letter-spacing: 5px;
            margin: 20px 0;
        }

        .footer {
            font-size: 12px;
            color: #525252;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2 style="color: #f59e0b;">CabManager Elite</h2>
        <p>Use the code below to verify your account and join the fleet.</p>
        <div class="code">{{ $code }}</div>
        <p>This code will expire in 10 minutes.</p>
        <div class="footer">If you didn't request this, please ignore this email.</div>
    </div>
</body>

</html>
