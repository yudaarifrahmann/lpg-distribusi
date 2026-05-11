<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AppSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public const DEFAULTS = [
        'site_name' => 'Agen LPG Amanah',
        'logo_path' => null,
        'landing_badge' => 'Agen LPG Resmi dan Terpercaya',
        'landing_title' => 'Distribusi LPG untuk Pangkalan Lebih Tertib',
        'landing_subtitle' => 'Kelola stok tabung, DO, pengiriman, penjualan, dan piutang pangkalan dalam satu sistem operasional agen LPG.',
        'landing_primary_button' => 'Masuk Sistem',
        'landing_secondary_button' => 'Lihat Layanan',
        'landing_feature_1_title' => 'Stok Tabung Terkendali',
        'landing_feature_1_body' => 'Pantau stok gudang, mutasi, retur, dan stok kendaraan tanpa selisih pencatatan.',
        'landing_feature_2_title' => 'Pengiriman Pangkalan',
        'landing_feature_2_body' => 'Surat jalan, supir, kendaraan, dan alokasi tabung tersusun untuk setiap rute distribusi.',
        'landing_feature_3_title' => 'Piutang dan Laporan',
        'landing_feature_3_body' => 'Rekap penjualan, pembayaran cicilan, serta laporan laba rugi siap dipantau harian.',
        'landing_stat_1_value' => '3 Kg',
        'landing_stat_1_label' => 'Produk Subsidi',
        'landing_stat_2_value' => 'Real-time',
        'landing_stat_2_label' => 'Monitoring Stok',
        'landing_stat_3_value' => 'Rute',
        'landing_stat_3_label' => 'Distribusi Harian',
        'landing_stat_4_value' => 'Audit',
        'landing_stat_4_label' => 'Riwayat Transaksi',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $defaults = self::DEFAULTS;
        $fallback = $defaults[$key] ?? $default;

        if (! Schema::hasTable('app_settings')) {
            return $fallback;
        }

        return Cache::rememberForever("app_setting_{$key}", function () use ($key, $fallback) {
            return self::query()->where('key', $key)->value('value') ?? $fallback;
        });
    }

    public static function setValue(string $key, mixed $value): void
    {
        self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("app_setting_{$key}");
    }

    public static function landingValues(): array
    {
        return collect(self::DEFAULTS)
            ->mapWithKeys(fn ($default, $key) => [$key => self::getValue($key, $default)])
            ->all();
    }
}
