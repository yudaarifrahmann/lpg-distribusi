<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogActivity
{
    public static function log($aktivitas, $module, $dataSebelum = null, $dataSesudah = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'aktivitas' => $aktivitas,
            'module' => $module,
            'data_sebelum' => $dataSebelum,
            'data_sesudah' => $dataSesudah,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
