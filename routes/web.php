<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PangkalanController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\LpgPriceController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ScheduleAgreementController;
use App\Http\Controllers\PenebusanController;
use App\Http\Controllers\SuratJalanController;
use App\Http\Controllers\VehicleStockController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\PembayaranPiutangController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReturTabungController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    return view('landing', [
        'settings' => \App\Models\AppSetting::landingValues(),
    ]);
});

Route::get('/syarat-ketentuan', function () {
    return view('terms');
})->name('terms');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Forgot Password
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showOptions'])->name('forgot-password.options');
    Route::get('/forgot-password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showEmailForm'])->name('forgot-password.email-form');
    Route::post('/forgot-password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendOTP'])->name('forgot-password.send-otp');
    Route::get('/forgot-password/verify', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showVerifyOTPForm'])->name('forgot-password.verify-form');
    Route::post('/forgot-password/verify', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyOTP'])->name('forgot-password.verify-otp');
    Route::get('/forgot-password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('forgot-password.reset-form');
    Route::post('/forgot-password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])->name('forgot-password.reset-password');
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Schedule Agreement
    Route::resource('schedule-agreement', ScheduleAgreementController::class)->middleware('can:view sa');

    // Penebusan
    Route::resource('penebusan', PenebusanController::class)->middleware('can:view penebusan');

    // Surat Jalan
    Route::resource('surat-jalan', SuratJalanController::class)->middleware('can:view surat jalan');
    Route::get('surat-jalan/{suratJalan}/print', [SuratJalanController::class, 'print'])->name('surat-jalan.print')->middleware('can:view surat jalan');
    Route::get('surat-jalan/{suratJalan}/download', [SuratJalanController::class, 'download'])->name('surat-jalan.download')->middleware('can:view surat jalan');
    Route::patch('surat-jalan/{suratJalan}/update-status', [SuratJalanController::class, 'updateStatus'])->name('surat-jalan.update-status')->middleware('can:view surat jalan');

    // Vehicle Stock (Return Route Only)
    Route::post('/vehicle-stock/{truck}/return', [VehicleStockController::class, 'returnStock'])->name('vehicle-stock.return');

    // Stock Dashboard (Unified)
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');

    // Penjualan
    Route::get('penjualan/print-rekap', [PenjualanController::class, 'printRekap'])->name('penjualan.print-rekap')->middleware('can:view penjualan');
    Route::resource('penjualan', PenjualanController::class)->middleware('can:view penjualan');
    Route::get('penjualan/{penjualan}/print', [PenjualanController::class, 'print'])->name('penjualan.print')->middleware('can:view penjualan');
    Route::post('/penjualan/{penjualan}/verify-transfer', [PenjualanController::class, 'verifyTransfer'])->name('penjualan.verify-transfer')->middleware('role:superadmin|admin_keuangan');

    // Piutang
    Route::resource('piutang', PiutangController::class)->only(['index', 'show'])->middleware('can:view piutang');
    
    // Pembayaran Piutang
    Route::post('/pembayaran-piutang', [PembayaranPiutangController::class, 'store'])->name('pembayaran-piutang.store')->middleware('can:view piutang');
    Route::post('/piutang/{piutang}/pelunasan', [PembayaranPiutangController::class, 'pelunasan'])->name('piutang.pelunasan')->middleware('can:view piutang');
    Route::post('/pembayaran-piutang/{pembayaran}/verify', [PembayaranPiutangController::class, 'verify'])->name('pembayaran-piutang.verify')->middleware('role:superadmin|admin_keuangan');
    Route::delete('/pembayaran-piutang/{pembayaran}', [PembayaranPiutangController::class, 'destroy'])->name('pembayaran-piutang.destroy')->middleware('can:delete piutang');

    // Pengeluaran
    Route::resource('expense', ExpenseController::class)->middleware('can:view pengeluaran');
    Route::post('/expense/{expense}/verify', [ExpenseController::class, 'verify'])->name('expense.verify')->middleware('can:edit pengeluaran');

    // Retur Tabung
    Route::resource('retur', ReturTabungController::class);
    Route::post('/retur/{retur}/approve', [ReturTabungController::class, 'approve'])->name('retur.approve')->middleware('can:edit stock');

    // Stock Adjustment & History
    Route::resource('stock-adjustment', StockAdjustmentController::class);
    Route::get('/stock-history', [StockHistoryController::class, 'index'])->name('stock-history.index')->middleware('can:view stock');
    
    // Audit Log
    Route::resource('audit-log', AuditLogController::class)->only(['index', 'show']);

    // User Management
    Route::resource('user-management', UserManagementController::class)
        ->except(['show'])
        ->parameters(['user-management' => 'user'])
        ->middleware('can:view user management');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Settings
    Route::middleware('role:superadmin')->prefix('settings')->group(function () {
        Route::get('/landing', [AppSettingController::class, 'edit'])->name('settings.landing.edit');
        Route::put('/landing', [AppSettingController::class, 'update'])->name('settings.landing.update');
        Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup', [BackupController::class, 'create'])->name('backup.create');
        Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
        Route::delete('/backup/{filename}', [BackupController::class, 'destroy'])->name('backup.destroy');
    });

    // Laporan
    Route::middleware('can:view laporan')->prefix('laporan')->group(function () {
        Route::get('/global', [ReportController::class, 'globalReport'])->name('report.global');
        Route::get('/global/export', [ReportController::class, 'exportGlobal'])->name('report.global.export');
        Route::get('/penjualan', [ReportController::class, 'penjualan'])->name('report.penjualan');
        Route::get('/penjualan/export', [ReportController::class, 'exportPenjualan'])->name('report.penjualan.export');
        Route::get('/pengeluaran', [ReportController::class, 'pengeluaran'])->name('report.pengeluaran');
        Route::get('/laba-rugi', [ReportController::class, 'labaRugi'])->name('report.laba-rugi');
        Route::get('/piutang', [ReportController::class, 'piutang'])->name('report.piutang');
        Route::get('/stok', [ReportController::class, 'stok'])->name('report.stok');
    });

    // Master Data Routes — guarded by 'view master data' permission
    Route::middleware('can:view master data')->prefix('master-data')->group(function () {
        // Pangkalan
        Route::resource('pangkalan', PangkalanController::class);

        // Truk
        Route::resource('truck', TruckController::class);

        // Supir/Knek
        Route::resource('driver', DriverController::class);

        // Harga LPG
        Route::resource('lpg-price', LpgPriceController::class)->parameters([
            'lpg-price' => 'lpgPrice',
        ]);

        // Kategori Pengeluaran
        Route::resource('expense-category', ExpenseCategoryController::class)->parameters([
            'expense-category' => 'expenseCategory',
        ]);

        // Cabang
        Route::resource('branch', App\Http\Controllers\BranchController::class);
    });
});
