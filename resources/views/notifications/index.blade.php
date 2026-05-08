@extends('layouts.admin')
@section('title', 'Notifikasi - LPG Distribution')
@section('page_title', 'Pusat Notifikasi')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest">Notifikasi Terbaru</h4>
        @if(Auth::user()->unreadNotifications->count() > 0)
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="text-xs font-bold text-blue-600 hover:underline">Tandai semua telah dibaca</button>
        </form>
        @endif
    </div>

    <div class="space-y-4">
        @forelse($notifications as $notif)
        <div class="bg-white p-6 rounded-3xl shadow-sm border {{ $notif->read_at ? 'border-gray-100 opacity-60' : 'border-blue-100 bg-blue-50/20' }} flex justify-between items-start transition">
            <div class="flex space-x-4">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center {{ $notif->data['type'] == 'warning' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600' }}">
                    @if($notif->data['type'] == 'warning')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                </div>
                <div>
                    <h5 class="text-sm font-black text-gray-900">{{ $notif->data['title'] }}</h5>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ $notif->data['message'] }}</p>
                    <div class="mt-3 flex items-center space-x-4">
                        <a href="{{ $notif->data['url'] }}" class="text-[10px] font-bold text-blue-600 uppercase tracking-widest hover:underline">Buka Detail</a>
                        <span class="text-[10px] text-gray-400 italic">{{ $notif->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @if(!$notif->read_at)
            <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                @csrf
                <button type="submit" class="p-2 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </button>
            </form>
            @endif
        </div>
        @empty
        <div class="bg-white p-12 rounded-3xl shadow-sm border border-gray-100 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <p class="text-sm font-bold text-gray-400 italic">Belum ada notifikasi baru.</p>
        </div>
        @endforelse

        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
