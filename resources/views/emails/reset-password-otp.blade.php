<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 40px 0;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-w-5xl: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin: 0 auto; max-width: 600px;">
        <tr>
            <td style="background-color: #2563eb; padding: 30px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">LPG Distrib</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 40px 30px;">
                <h2 style="color: #333333; font-size: 20px; margin-top: 0;">Permintaan Reset Password</h2>
                <p style="color: #555555; font-size: 16px; line-height: 1.5;">Halo,</p>
                <p style="color: #555555; font-size: 16px; line-height: 1.5;">Kami menerima permintaan untuk melakukan reset password pada akun Anda. Berikut adalah kode OTP Anda. Kode ini hanya berlaku selama 10 menit.</p>
                
                <div style="background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 20px; text-align: center; margin: 30px 0;">
                    <h3 style="margin: 0; font-size: 32px; letter-spacing: 5px; color: #1e293b;">{{ $otp }}</h3>
                </div>
                
                <p style="color: #555555; font-size: 14px; line-height: 1.5; margin-bottom: 0;">Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.</p>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #f1f5f9;">
                <p style="color: #94a3b8; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} LPG Distrib. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
