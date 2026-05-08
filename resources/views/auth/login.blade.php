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

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition"
                        placeholder="Masukkan password"
                    >
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
