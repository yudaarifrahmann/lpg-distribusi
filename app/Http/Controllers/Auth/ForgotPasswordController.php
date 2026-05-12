<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordOTP;

class ForgotPasswordController extends Controller
{
    public function showOptions()
    {
        $whatsappNumber = AppSetting::getValue('whatsapp_admin', '6281234567890');
        return view('auth.forgot-password.options', compact('whatsappNumber'));
    }

    public function showEmailForm()
    {
        return view('auth.forgot-password.email');
    }

    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak ditemukan di sistem kami.',
        ]);

        $otp = rand(100000, 999999);
        $email = $request->email;

        // Save OTP to Cache for 10 minutes
        Cache::put('otp_reset_' . $email, $otp, now()->addMinutes(10));

        // Send Email
        try {
            Mail::to($email)->send(new ResetPasswordOTP($otp));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email OTP. Pastikan konfigurasi SMTP sudah benar.')->withInput();
        }

        return redirect()->route('forgot-password.verify-form', ['email' => $email])
                         ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showVerifyOTPForm(Request $request)
    {
        $email = $request->query('email');
        if (!$email) {
            return redirect()->route('forgot-password.email-form');
        }

        return view('auth.forgot-password.verify', compact('email'));
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6',
        ]);

        $email = $request->email;
        $cachedOtp = Cache::get('otp_reset_' . $email);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return back()->with('error', 'Kode OTP salah atau sudah kadaluarsa.')->withInput();
        }

        // OTP Valid, mark as verified in session to allow password reset
        session(['otp_verified_email' => $email]);

        return redirect()->route('forgot-password.reset-form');
    }

    public function showResetForm()
    {
        $email = session('otp_verified_email');
        if (!$email) {
            return redirect()->route('forgot-password.email-form')->with('error', 'Sesi Anda telah habis, silakan mulai ulang.');
        }

        return view('auth.forgot-password.reset', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $email = session('otp_verified_email');
        if (!$email) {
            return redirect()->route('forgot-password.email-form')->with('error', 'Sesi Anda telah habis, silakan mulai ulang.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Clear session and cache
        session()->forget('otp_verified_email');
        Cache::forget('otp_reset_' . $email);

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
