<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat & Ketentuan - LPG Distrib</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        .bg-mesh {
            background-color: #ffffff;
            background-image: radial-gradient(at 0% 0%, hsla(210,100%,95%,1) 0, transparent 50%), 
                              radial-gradient(at 100% 100%, hsla(220,100%,95%,1) 0, transparent 50%);
        }
    </style>
</head>
<body class="bg-mesh text-slate-900 overflow-x-hidden">

    {{-- Navigation --}}
    <nav class="fixed w-full z-50 glass border-b border-slate-200/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-1.945-1.164c-.143-.225-.35-.373-.572-.444a1 1 0 00-1.287.8 3.007 3.007 0 00-.164 1.084c.058 1.233.662 2.342 1.593 3.11.525.434 1.156.753 1.826.927a6.02 6.02 0 002.75.09c.925-.196 1.79-.673 2.456-1.37.726-.762 1.19-1.753 1.292-2.882a5.01 5.01 0 00-.49-2.678c-.293-.556-.674-1.047-1.063-1.468a13.372 13.372 0 00-.964-.94c.067-.434.144-.872.233-1.29.178-.84.388-1.59.604-2.166.11-.293.214-.538.302-.712a2.38 2.38 0 01.082-.134z"/></svg>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-slate-900 uppercase">LPG <span class="text-blue-600">Distrib</span></span>
                </a>
                <div class="hidden md:flex items-center space-x-10 text-sm font-semibold text-slate-600 uppercase tracking-widest">
                    <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Beranda</a>
                </div>
                <div>
                    @auth
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-slate-900 text-white rounded-full text-sm font-bold shadow-xl hover:bg-black transition-all">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-600 text-white rounded-full text-sm font-bold shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all">Masuk Sistem</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="pt-32 pb-20 px-6">
        <div class="max-w-4xl mx-auto">
            <div class="mb-12 text-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 mb-4 tracking-tight">Syarat & Ketentuan</h1>
                <p class="text-slate-500">Terakhir diperbarui: 11 Mei 2026</p>
            </div>

            <div class="bg-white/80 backdrop-blur-sm border border-slate-200 rounded-3xl p-6 sm:p-8 lg:p-12 shadow-sm space-y-8 text-slate-600 leading-relaxed">
                <section>
                    <h2 class="text-2xl font-bold text-slate-900 mb-4">1. Penerimaan Ketentuan</h2>
                    <p>Dengan mengakses dan menggunakan sistem LPG Distrib, Anda dianggap telah membaca, memahami, dan menyetujui untuk terikat oleh Syarat dan Ketentuan ini. Jika Anda tidak menyetujui bagian apa pun dari ketentuan ini, Anda tidak diperkenankan menggunakan sistem ini.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-slate-900 mb-4">2. Penggunaan Layanan</h2>
                    <p>Sistem ini disediakan khusus untuk manajemen distribusi LPG. Anda setuju untuk:</p>
                    <ul class="list-disc ml-6 mt-4 space-y-2">
                        <li>Menggunakan sistem hanya untuk tujuan yang sah dan sesuai dengan hukum yang berlaku.</li>
                        <li>Menjaga kerahasiaan informasi login akun Anda.</li>
                        <li>Bertanggung jawab atas semua aktivitas yang terjadi di bawah akun Anda.</li>
                        <li>Tidak mencoba mengganggu keamanan atau integritas sistem.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-slate-900 mb-4">3. Data dan Privasi</h2>
                    <p>Kami menghormati privasi data Anda. Semua informasi yang dimasukkan ke dalam sistem akan dikelola sesuai dengan kebijakan privasi kami. Data distribusi, stok, dan keuangan adalah milik pengguna, namun kami berhak memproses data tersebut untuk keperluan fungsionalitas sistem.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-slate-900 mb-4">4. Batasan Tanggung Jawab</h2>
                    <p>LPG Distrib disediakan "apa adanya" tanpa jaminan dalam bentuk apa pun. Kami tidak bertanggung jawab atas kerugian finansial, kehilangan data, atau gangguan bisnis yang timbul dari penggunaan atau ketidakmampuan menggunakan sistem ini.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-slate-900 mb-4">5. Perubahan Ketentuan</h2>
                    <p>Kami berhak untuk mengubah atau memperbarui Syarat dan Ketentuan ini kapan saja tanpa pemberitahuan sebelumnya. Perubahan akan berlaku segera setelah dipublikasikan di halaman ini.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-slate-900 mb-4">6. Kontak</h2>
                    <p>Jika Anda memiliki pertanyaan mengenai Syarat dan Ketentuan ini, silakan hubungi tim dukungan kami melalui dashboard atau saluran komunikasi resmi yang tersedia.</p>
                </section>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center text-blue-600 font-bold hover:underline">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="py-12 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 flex flex-col md:flex-row justify-between gap-6">
            <div>
                <p class="text-xl font-black text-slate-950">{{ $settings['site_name'] ?? 'LPG Distrib' }}</p>
                <p class="text-sm text-slate-500 mt-2">Sistem manajemen distribusi untuk agen LPG.</p>
            </div>
            <div class="flex items-center gap-5 text-xs font-bold text-slate-500 uppercase">
                <a href="{{ route('terms') }}" class="hover:text-teal-700 transition">Syarat & Ketentuan</a>
                <span>&copy; 2026</span>
            </div>
        </div>
    </footer>

</body>
</html>
