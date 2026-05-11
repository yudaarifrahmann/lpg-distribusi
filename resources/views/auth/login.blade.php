@extends('app')

@section('title', 'Login - LPG Distrib')

@section('extra_css')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
    .login-card { 
        background: #ffffff;
        border: 1px solid #f1f5f9;
    }
    .input-focus:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.05);
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
                    <path d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-1.945-1.164c-.143-.225-.35-.373-.572-.444a1 1 0 00-1.287.8 3.007 3.007 0 00-.164 1.084c.058 1.233.662 2.342 1.593 3.11.525.434 1.156.753 1.826.927a6.02 6.02 0 002.75.09c.925-.196 1.79-.673 2.456-1.37.726-.762 1.19-1.753 1.292-2.882a5.01 5.01 0 00-.49-2.678c-.293-.556-.674-1.047-1.063-1.468a13.372 13.372 0 00-.964-.94c.067-.434.144-.872.233-1.29.178-.84.388-1.59.604-2.166.11-.293.214-.538.302-.712a2.38 2.38 0 01.082-.134z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">LPG <span class="text-blue-600">Distrib</span></h1>
            <p class="text-slate-500 mt-2 font-medium">Sistem Manajemen Distribusi Terpadu</p>
        </div>

        {{-- Login Card --}}
        <div class="login-card rounded-[32px] p-6 sm:p-8 lg:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.04)]">
            {{-- Alert Messages --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center space-x-3">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <p class="text-red-600 text-xs font-semibold">{{ $errors->first('email') ?? $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-900 uppercase tracking-widest mb-2 ml-1">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-600 transition-all outline-none"
                        placeholder="nama@email.com"
                    >
                </div>

                <div x-data="{ show: false }">
                    <div class="flex items-center justify-between mb-2 ml-1">
                        <label for="password" class="text-xs font-bold text-slate-900 uppercase tracking-widest">Password</label>
                        <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">Lupa Password?</a>
                    </div>
                    <div class="relative">
                        <input 
                            :type="show ? 'text' : 'password'" 
                            id="password" 
                            name="password" 
                            required 
                            class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-600 transition-all outline-none pr-14"
                            placeholder="••••••••"
                        >
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors focus:outline-none">
                            <template x-if="!show">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </template>
                            <template x-if="show">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </template>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember" 
                        class="w-5 h-5 text-blue-600 border-slate-200 rounded-lg focus:ring-blue-600/20"
                    >
                    <label for="remember" class="ml-3 text-sm font-medium text-slate-600">Tetap masuk selama 30 hari</label>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30 hover:-translate-y-0.5 transition-all duration-200 uppercase tracking-widest text-sm"
                >
                    Masuk Ke Sistem
                </button>
            </form>
        </div>

        {{-- Demo Credentials --}}
        <div class="mt-12">
            <div class="flex items-center space-x-4 mb-6">
                <div class="h-px flex-1 bg-slate-100"></div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Demo Access</span>
                <div class="h-px flex-1 bg-slate-100"></div>
            </div>
            
            <div class="grid grid-cols-1 gap-3">
                <div class="p-4 rounded-2xl bg-slate-50/50 border border-slate-100 group hover:bg-white hover:shadow-md hover:border-blue-100 transition-all cursor-pointer" onclick="document.getElementById('email').value='superadmin@lpg.local'; document.getElementById('password').value='superadmin123'">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-blue-600 uppercase mb-1">SuperAdmin</p>
                            <p class="text-xs font-semibold text-slate-900">superadmin@lpg.local</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="p-4 rounded-2xl bg-slate-50/50 border border-slate-100 group hover:bg-white hover:shadow-md hover:border-blue-100 transition-all cursor-pointer" onclick="document.getElementById('email').value='admin_keuangan@lpg.local'; document.getElementById('password').value='keuangan123'">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1 group-hover:text-blue-600 transition-colors">Keuangan</p>
                        <p class="text-[11px] font-semibold text-slate-900">admin_keuangan@lpg.local</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50/50 border border-slate-100 group hover:bg-white hover:shadow-md hover:border-blue-100 transition-all cursor-pointer" onclick="document.getElementById('email').value='supir_knek@lpg.local'; document.getElementById('password').value='supirknek123'">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1 group-hover:text-blue-600 transition-colors">Driver</p>
                        <p class="text-[11px] font-semibold text-slate-900">supir_knek@lpg.local</p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Back to Home --}}
        <div class="mt-10 text-center">
            <a href="{{ url('/') }}" class="text-xs font-bold text-slate-400 hover:text-blue-600 transition uppercase tracking-widest flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
