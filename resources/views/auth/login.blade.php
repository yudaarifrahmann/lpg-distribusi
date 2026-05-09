@extends('app')

@section('title', 'Login - LPG Distribution')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">LPG Distribution</h1>
                <p class="text-gray-500 mt-2">Sistem Manajemen Distribusi LPG</p>
            </div>

            <!-- Alert Messages -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-600 text-sm font-medium">{{ $errors->first('email') ?? $errors->first() }}</p>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition"
                        placeholder="Masukkan email Anda"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <input 
                            type="password"
                            :type="show ? 'text' : 'password'" 
                            id="password" 
                            name="password" 
                            required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition pr-10"
                            placeholder="Masukkan password"
                        >
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <template x-if="!show">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </template>
                            <template x-if="show">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </template>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition duration-200 mt-6"
                >
                    Masuk
                </button>
            </form>

            <!-- Demo Credentials Info -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-600 font-semibold mb-3">Demo Credentials:</p>
                <div class="space-y-2 text-xs text-gray-700">
                    <div class="bg-gray-50 p-2 rounded">
                        <p><strong>SuperAdmin:</strong></p>
                        <p>Email: superadmin@lpg.local</p>
                        <p>Password: superadmin123</p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded">
                        <p><strong>Admin Keuangan:</strong></p>
                        <p>Email: admin_keuangan@lpg.local</p>
                        <p>Password: keuangan123</p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded">
                        <p><strong>Supir/Knek:</strong></p>
                        <p>Email: supir_knek@lpg.local</p>
                        <p>Password: supirknek123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
