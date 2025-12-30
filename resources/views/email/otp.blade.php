<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>کد تایید</title>
    <style>

        body {
            font-family: 'Tahoma', sans-serif;
            background: linear-gradient(135deg, #2196F3, #00BCD4);
            margin: 0;
            padding: 0;
            direction: rtl; /* راست‌چین کردن محتوای صفحه */
            color: #fff;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 80px auto;
            text-align: center;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }
        .header h2 {
            color: #2196F3;
            font-size: 32px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        .otp-code {
            font-size: 35px;
            font-weight: 650;
            color: white;
            background: #007bb5;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            display: inline-block;
            margin-top: 20px;
            letter-spacing: 3px;
        }
        .content {
            margin-top: 25px;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
            letter-spacing: 0.5px;
        }
        .footer a {
            color: #007bb5;
            text-decoration: none;
            font-weight: bold;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .container:before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            right: -50px;
            bottom: -50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            z-index: -1;
        }
        .container:hover:before {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>کد تایید شما</h2>
    </div>
    <div class="content">
        <p>به ریرا خوش آمدید</p>
        <p>برای تایید درخواست خود </p>
        <p>لطفاً کد تایید زیر را وارد کنید</p>
        <div class="otp-code">
            {{ $user->sms_sent_code }} <!-- فرض بر این است که کد OTP در مدل کاربر موجود است -->
        </div>
        <p>این کد تنها برای 3 دقیقه معتبر است</p>
    </div>
    <div class="footer">
        <p>با تشکر از استفاده شما از خدمات ما</p>
    </div>
</div>
</body>
</html>
