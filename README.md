# LPG Distribution Management System (Laravel 12)

Sistem informasi manajemen distribusi LPG skala perusahaan dengan fitur inventaris, penjualan, keuangan, dan pelaporan terpadu.

## Fitur Utama
- **Dashboard BI**: Visualisasi tren penjualan, top pangkalan, dan laba rugi bulanan.
- **Manajemen Inventaris**: Sinkronisasi stok gudang dan kendaraan secara real-time.
- **Keuangan Terpadu**: Pencatatan piutang (cicilan), pengeluaran operasional, dan laba-rugi.
- **Audit Trail**: Pencatatan log aktivitas user untuk keamanan data (tambah, edit, hapus).
- **Notifikasi Pintar**: Peringatan piutang jatuh tempo dan stok menipis.
- **Mobile Responsive**: Optimasi tampilan untuk supir/knek via smartphone.

## Persyaratan Sistem
- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM

## Cara Instalasi
1. Clone repositori ini.
2. Jalankan `composer install`.
3. Salin `.env.example` ke `.env` dan sesuaikan konfigurasi database.
4. Jalankan `php artisan key:generate`.
5. Jalankan `php artisan migrate --seed`.
6. Jalankan `npm install && npm run build`.
7. Jalankan `php artisan storage:link`.

## Akun Default
- **SuperAdmin**: `admin@lpg.com` / `password`
- **Finance**: `keuangan@lpg.com` / `password`
- **Driver**: `supir@lpg.com` / `password`

## Deployment Checklist
- Set `APP_ENV=production` dan `APP_DEBUG=false`.
- Jalankan `php artisan optimize` (config, routes, views cache).
- Jalankan `php artisan queue:work` (untuk notifikasi & background jobs).
- Set up Cron Job untuk scheduler: `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`.

## Backup & Restore
- Gunakan menu **Backup Data** di dashboard SuperAdmin untuk mendownload snapshot SQL.
- Restore manual menggunakan phpMyAdmin atau command line: `mysql -u user -p db_name < backup.sql`.

## Security Notes
- Seluruh transaksi menggunakan Database Transaction untuk menjaga integritas data.
- Dilengkapi dengan Rate Limiting pada login dan CSRF protection.
- Audit log mencatat IP Address dan User Agent setiap perubahan data penting.

---
Dikembangkan dengan **Laravel 12**, **Tailwind CSS**, dan **Clean Architecture**.
