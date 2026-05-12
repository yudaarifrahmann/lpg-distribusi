@extends('app')

@section('title', 'Verifikasi OTP - LPG Distrib')

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
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Verifikasi OTP</h1>
            <p class="text-slate-500 mt-2 font-medium text-sm">Masukkan 6 digit kode OTP yang dikirim ke <br><span class="font-bold text-slate-700">{{ $email }}</span></p>
        </div>

        {{-- Form Card --}}
        <div class="login-card rounded-[32px] p-6 sm:p-8 lg:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.04)]">
            
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <p class="text-emerald-600 text-xs font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center space-x-3">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <p class="text-red-600 text-xs font-semibold">{{ $errors->first('otp') ?? $errors->first() }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center space-x-3">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <p class="text-red-600 text-xs font-semibold">{{ session('error') }}</p>
                </div>
            @endif

            <form action="{{ route('forgot-password.verify-otp') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <div>
                    <label for="otp" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-2 ml-1 text-center">Kode OTP</label>
                    <input 
                        type="text" 
                        id="otp" 
                        name="otp" 
                        required 
                        maxlength="6"
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-600 transition-all outline-none text-center text-2xl tracking-[0.5em] font-black placeholder:text-slate-300 placeholder:tracking-normal placeholder:font-medium placeholder:text-sm"
                        placeholder="Masukkan 6 Digit Angka"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30 hover:-translate-y-0.5 transition-all duration-200 uppercase tracking-widest text-sm"
                >
                    Verifikasi OTP
                </button>
            </form>
        </div>
        
        <div class="mt-8 text-center">
            <a href="{{ route('forgot-password.options') }}" class="text-xs font-bold text-slate-400 hover:text-blue-600 transition uppercase tracking-widest flex items-center justify-center">
                Batal dan Kembali
            </a>
        </div>
    </div>
</div>
@endsection
