@extends('layouts.admin')
@section('title', 'Audit Log - LPG Distribution')
@section('page_title', 'System Activity Audit Log')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Waktu</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">User</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Aktivitas</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Module</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">IP Address</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($logs as $log)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-6 py-4 text-gray-500">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-[10px] font-bold text-gray-600 mr-2 uppercase">
                                {{ substr($log->user->name ?? '?', 0, 1) }}
                            </div>
                            <span class="font-bold text-gray-700">{{ $log->user->name ?? 'System' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $log->aktivitas }}</td>
                    <td class="px-6 py-4 uppercase">
                        <span class="px-2 py-0.5 bg-gray-100 rounded text-[9px] font-black text-gray-500">{{ $log->module }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 font-mono">{{ $log->ip_address }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('audit-log.show', $log->id) }}" class="text-blue-600 hover:underline font-bold">Lihat JSON</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $logs->links() }}
    </div>
</div>
@endsection
