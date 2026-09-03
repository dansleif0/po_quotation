<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeingNautic - Solusi Administrasi Maritim Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-nav { 
            background: rgba(255, 255, 255, 0.85); 
            backdrop-filter: blur(16px); 
            -webkit-backdrop-filter: blur(16px); 
            border-bottom: 1px solid rgba(0, 0, 0, 0.05); 
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(12px); 
            -webkit-backdrop-filter: blur(12px); 
            border: 1px solid rgba(0, 0, 0, 0.05); 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        }
        .hero-bg {
            background-image: linear-gradient(to bottom, rgba(239, 246, 255, 0.85), rgba(255, 255, 255, 1)), url('https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?auto=format&fit=crop&q=80&w=2670');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .text-gradient {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-blue-500 selection:text-white">

    <nav class="fixed w-full z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-6 h-24 flex items-center justify-between">
            <div class="flex items-center gap-4">
                {{-- Gambar Logo --}}
                <div class="p-1">
                    <img src="{{ asset('images/beingnautic.png') }}"
                         alt="BeingNautic Logo"
                         class="h-14 w-auto object-contain">
                </div>
                <span class="text-2xl font-extrabold tracking-wide text-slate-900">Being<span class="text-blue-600">Nautic</span></span>
            </div>
            <div class="hidden md:flex items-center gap-10 font-medium text-slate-600">
                <a href="#fitur" class="hover:text-blue-600 transition duration-300">Fitur Kelautan</a>
                <a href="{{ route('login') }}" class="relative group px-6 py-2.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white transition-all duration-300 overflow-hidden shadow-lg">
                    <span class="relative z-10">Masuk Portal</span>
                </a>
            </div>
        </div>
    </nav>

    <section class="hero-bg pt-44 pb-32 px-6 min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto text-center w-full">
            <div class="inline-flex items-center gap-2 bg-blue-100 border border-blue-200 text-blue-700 px-5 py-2.5 rounded-full text-sm font-semibold tracking-widest uppercase mb-8 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Revolusi Administrasi Maritim
            </div>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Kelola Armada & Produk <br> <span class="text-gradient">Lebih Cerdas.</span>
            </h1>
            <p class="mt-8 text-xl md:text-2xl text-slate-600 max-w-3xl mx-auto font-light leading-relaxed">
                Platform terintegrasi untuk manajemen <strong class="text-slate-800 font-medium">Quotation, Invoice, SPK, dan BAST</strong> khusus produk kelautan. Kendalikan administrasi kapal dan perairan dengan presisi tinggi.
            </p>
            <div class="mt-14 flex flex-col sm:flex-row gap-6 justify-center">
                <a href="{{ route('dashboard') }}" class="group bg-blue-600 hover:bg-blue-700 text-white px-10 py-5 rounded-2xl font-bold text-lg transition-all duration-300 shadow-[0_0_20px_rgba(37,99,235,0.3)] hover:shadow-[0_0_30px_rgba(37,99,235,0.5)] flex items-center justify-center gap-3 transform hover:-translate-y-1">
                    Mulai Berlayar
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-32 relative px-6 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-20">
                <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6">Navigasi Bisnis <span class="text-blue-600">Kelautan Anda</span></h2>
                <p class="text-slate-500 text-lg max-w-2xl mx-auto">Sistem terpusat yang dirancang khusus untuk memenuhi standar administrasi industri perkapalan dan produk maritim.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="glass-card p-10 rounded-3xl hover:border-blue-200 transition-all duration-500 group transform hover:-translate-y-2">
                    <div class="h-16 w-16 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-8 shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-slate-900">Quotation Produk Laut</h3>
                    <p class="text-slate-600 leading-relaxed">Buat penawaran harga untuk suku cadang kapal dan alat keselamatan dengan cepat. Ekspor PDF dengan logo dan kop surat resmi secara instan.</p>
                </div>

                <!-- Card 2 -->
                <div class="glass-card p-10 rounded-3xl hover:border-indigo-200 transition-all duration-500 group transform hover:-translate-y-2">
                    <div class="h-16 w-16 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 mb-8 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-slate-900">Manajemen Invoice & BAST</h3>
                    <p class="text-slate-600 leading-relaxed">Terbitkan tagihan dan Berita Acara Serah Terima untuk proyek kelautan dengan otomatisasi data. Kurangi human-error, tingkatkan profesionalitas.</p>
                </div>

                <!-- Card 3 -->
                <div class="glass-card p-10 rounded-3xl hover:border-emerald-200 transition-all duration-500 group transform hover:-translate-y-2">
                    <div class="h-16 w-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 mb-8 shadow-sm group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-slate-900">SPK Terintegrasi</h3>
                    <p class="text-slate-600 leading-relaxed">Hasilkan Surat Perintah Kerja dari penawaran yang disetujui dalam hitungan detik. Seluruh histori tersimpan rapi dan mudah diakses kapan saja.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="border-t border-slate-200 bg-slate-50 text-slate-500 py-16 px-6">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-8 items-center">
            <div class="flex items-center gap-4 text-slate-900">
                <div class="p-1">
                    <img src="{{ asset('images/beingnautic.png') }}"
                         alt="BeingNautic Logo"
                         class="h-10 w-auto object-contain">
                </div>
                <span class="text-xl font-bold tracking-wider">BEING<span class="text-blue-600">NAUTIC</span></span>
            </div>
            
            <div class="flex flex-col md:flex-row gap-6 md:justify-end items-start md:items-center">
                <p>&copy; {{ date('Y') }} BeingNautic. Hak Cipta Dilindungi.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-blue-600 transition-colors">Privasi</a>
                    <a href="#" class="hover:text-blue-600 transition-colors">Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>