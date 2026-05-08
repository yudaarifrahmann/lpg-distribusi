@extends('layouts.admin')
@section('title', 'Detail Audit - LPG Distribution')
@section('page_title', 'Activity Data Snapshot')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('audit-log.index') }}" class="text-sm font-bold text-blue-600 hover:underline flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Log
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Data Sebelum Perubahan</h4>
            <pre class="bg-gray-900 text-emerald-400 p-6 rounded-2xl text-[10px] font-mono overflow-auto max-h-96">@json($audit_log->data_sebelum, JSON_PRETTY_PRINT)</pre>
        </div>
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Data Sesudah Perubahan</h4>
            <pre class="bg-gray-900 text-blue-400 p-6 rounded-2xl text-[10px] font-mono overflow-auto max-h-96">@json($audit_log->data_sesudah, JSON_PRETTY_PRINT)</pre>
        </div>
    </div>

    <div class="mt-8 bg-gray-900 rounded-3xl p-8 text-white">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">User Agent</p>
                <p class="text-xs text-gray-300 break-words">{{ $audit_log->user_agent }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">IP Address</p>
                <p class="text-xs text-gray-300 font-mono">{{ $audit_log->ip_address }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Timestamp</p>
                <p class="text-xs text-gray-300">{{ $audit_log->created_at->format('d/m/Y H:i:s') }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Activity ID</p>
                <p class="text-xs text-gray-300">#{{ $audit_log->id }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
