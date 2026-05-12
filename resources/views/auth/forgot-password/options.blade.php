@extends('app')

@section('title', 'Lupa Password - LPG Distrib')

@section('extra_css')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
    .login-card { 
        background: #ffffff;
        border: 1px solid #f1f5f9;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-[440px]">
        {{-- Logo & Header --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl shadow-xl shadow-blue-500/20 mb-6 transition-transform hover:scale-105 duration-300">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Lupa Password?</h1>
            <p class="text-slate-500 mt-2 font-medium text-sm">Pilih metode pemulihan akun Anda di bawah ini</p>
        </div>

        {{-- Options Card --}}
        <div class="login-card rounded-[32px] p-6 sm:p-8 lg:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.04)] space-y-4">
            
            <a href="{{ route('forgot-password.email-form') }}" class="block p-5 rounded-2xl border-2 border-slate-100 hover:border-blue-600 hover:bg-blue-50 transition-all duration-300 group">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 group-hover:bg-blue-600 flex items-center justify-center transition-colors duration-300">
                        <svg class="w-6 h-6 text-slate-500 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-blue-700 transition-colors">Reset Lewat Email</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Kirim kode OTP ke email terdaftar</p>
                    </div>
                    <svg class="w-5 h-5 ml-auto text-slate-300 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Halo Admin, saya ingin melakukan reset password untuk akun saya. Mohon bantuannya.') }}" target="_blank" class="block p-5 rounded-2xl border-2 border-slate-100 hover:border-emerald-500 hover:bg-emerald-50 transition-all duration-300 group">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 group-hover:bg-emerald-500 flex items-center justify-center transition-colors duration-300">
                        <svg class="w-6 h-6 text-slate-500 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">Reset Lewat Admin</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Hubungi Admin via WhatsApp</p>
                    </div>
                    <svg class="w-5 h-5 ml-auto text-slate-300 group-hover:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

        </div>
        
        {{-- Back to Login --}}
        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-xs font-bold text-slate-400 hover:text-blue-600 transition uppercase tracking-widest flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Login
            </a>
        </div>
    </div>
</div>
@endsection
