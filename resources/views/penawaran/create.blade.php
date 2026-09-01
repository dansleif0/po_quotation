@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 max-w-7xl">
    {{-- Header Page --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                <span class="p-2 bg-blue-600 text-white rounded-xl shadow-md shadow-blue-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </span>
                Buat Quotation Penawaran
            </h1>
            <p class="text-slate-500 text-sm mt-1">Formulir pembuatan quotation resmi dengan harga produk otomatis +40% mark-up.</p>
        </div>
        <a href="{{ route('histori.index') }}" class="self-start md:self-auto px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-sm transition-all flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Histori Quotation
        </a>
    </div>

    {{-- Alert Messages --}}
    @if (session('error'))
    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-xl shadow-sm">
        <p class="font-bold">Gagal Menyimpan</p>
        <p class="text-sm">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Main Form Container --}}
    <form action="{{ route('penawaran.store') }}" method="POST" id="quotationForm">
        @csrf

        {{-- Card 1: Informasi Dokumen & Klien --}}
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-6 md:p-8 mb-8">
            <h2 class="text-lg font-bold text-slate-800 mb-6 pb-3 border-b border-slate-100 flex items-center gap-2">
                <span class="p-1.5 bg-blue-100 text-blue-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3 3 0 10-6 0 3 3 0 006 0z" />
                    </svg>
                </span>
                Header Informasi Quotation
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- No Surat --}}
                <div>
                    <label for="no_surat" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                        No. Surat <span class="text-xs text-blue-600 font-normal">(Otomatis / Manual)</span>
                    </label>
                    <input type="text" name="no_surat" id="no_surat" value="{{ old('no_surat', $noSurat) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono font-bold text-blue-700 text-sm bg-slate-50/50">
                </div>

                {{-- Project No --}}
                <div>
                    <label for="project_no" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                        Project No <span class="text-xs text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" name="project_no" id="project_no" value="{{ old('project_no') }}" placeholder="Contoh: PRJ-2026-001"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-slate-800">
                </div>

                {{-- Perihal / Subject --}}
                <div>
                    <label for="perihal" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                        Perihal / Subject
                    </label>
                    <input type="text" name="perihal" id="perihal" value="{{ old('perihal', 'Penawaran Quotation Supply Cat & Pengecatan') }}" placeholder="Subject surat..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-slate-800">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Klien (Dropdown + Tombol (+)) --}}
                <div>
                    <label for="client_select" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                        Nama Klien / Perusahaan <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <select id="client_select" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-slate-800 appearance-none bg-white pr-10 shadow-sm font-medium">
                                <option value="">-- Pilih Klien Terdaftar --</option>
                                @foreach ($clients as $client)
                                <option value="{{ $client->id }}" data-nama="{{ $client->nama_klien }}" data-details="{{ $client->client_details ?? $client->alamat }}">
                                    {{ $client->nama_klien }}
                                </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Tombol (+) Tambah Klien Modal --}}
                        <button type="button" id="btnOpenClientModal" title="Tambah Klien Baru"
                            style="background-color: #059669 !important; color: #ffffff !important;"
                            class="px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2 text-sm shrink-0 whitespace-nowrap cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-bold">Tambah Klien Baru</span>
                        </button>
                    </div>

                    {{-- Input Hidden Nama Klien & Client ID --}}
                    <input type="hidden" name="client_id" id="client_id">
                    <input type="hidden" name="nama_klien" id="nama_klien">
                </div>

                {{-- Detail Alamat / Kontak Klien --}}
                <div>
                    <label for="client_details" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                        Detail Alamat / Kontak Klien
                    </label>
                    <textarea name="client_details" id="client_details" rows="3" placeholder="Alamat lengkap, Attn/UP, Telepon, Email..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-slate-800"></textarea>
                </div>
            </div>
        </div>

        {{-- Card 2: Rincian Produk Quotation --}}
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-6 md:p-8 mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 pb-3 border-b border-slate-100 gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <span class="p-1.5 bg-blue-100 text-blue-600 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </span>
                        Daftar Produk Quotation
                    </h2>
                    <p class="text-xs text-slate-500 font-medium flex items-center gap-1.5 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Note: Price per Liter otomatis terhitung berdasarkan Harga Normal dan Persen Up yang ditentukan.
                    </p>
                </div>

                <button type="button" id="btnAddRow"
                    class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md shadow-blue-600/20 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Produk
                </button>
            </div>

            {{-- Table Container --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse" id="itemsTable">
                    <thead>
                        <tr class="bg-slate-100/80 text-slate-700 uppercase text-[11px] font-bold tracking-wider">
                            <th class="p-3 rounded-l-xl w-8 text-center">#</th>
                            <th class="p-3 min-w-[200px]">Pilih Produk</th>
                            <th class="p-3 min-w-[110px]">Packing Size</th>
                            <th class="p-3 min-w-[90px] text-center">Qty Order</th>
                            <th class="p-3 min-w-[100px] text-right">Consumption (L)</th>
                            <th class="p-3 min-w-[130px]">Status</th>
                            <th class="p-3 min-w-[130px] text-right">Harga Normal / L</th>
                            <th class="p-3 min-w-[85px] text-center">Up (%)</th>
                            <th class="p-3 min-w-[140px] text-right">Harga Setelah Up / L</th>
                            <th class="p-3 min-w-[130px] text-right">Subtotal (Rp)</th>
                            <th class="p-3 rounded-r-xl w-10 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTbody" class="divide-y divide-slate-100">
                        {{-- Baris akan dibuat secara dinamis oleh JavaScript --}}
                    </tbody>
                </table>
            </div>

            {{-- Summary & Diskon --}}
            <div class="mt-8 pt-6 border-t border-slate-200/80 flex justify-end">
                <div class="w-full md:w-80 space-y-3 bg-slate-50 p-5 rounded-2xl border border-slate-200/60">
                    <div class="flex justify-between items-center text-sm font-semibold text-slate-600">
                        <span>Subtotal Keseluruhan:</span>
                        <span id="txtSubtotal" class="font-bold text-slate-900">Rp 0</span>
                    </div>

                    <div class="flex justify-between items-center text-sm gap-4">
                        <label for="diskon_global" class="font-semibold text-slate-600 whitespace-nowrap">Diskon Global (Rp):</label>
                        <input type="number" min="0" name="diskon_global" id="diskon_global" value="0" placeholder="0"
                            class="w-32 px-3 py-1.5 rounded-lg border border-slate-300 text-right text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="pt-3 border-t border-slate-200 flex justify-between items-center text-lg font-extrabold text-blue-700">
                        <span>Grand Total:</span>
                        <span id="txtGrandTotal">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-4 mb-12">
            <a href="{{ route('histori.index') }}"
                style="background-color: #f1f5f9 !important; color: #475569 !important;"
                class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition-all cursor-pointer">
                Batal
            </a>
            <button type="submit" id="btnSubmitQuotation"
                style="background-color: #2563eb !important; color: #ffffff !important;"
                class="px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-all shadow-xl shadow-blue-500/25 flex items-center gap-2.5 cursor-pointer shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current text-white" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="font-bold text-white tracking-wide">Simpan & Terbitkan Quotation</span>
            </button>
        </div>
    </form>
</div>

{{-- MODAL: Tambah Klien Baru --}}
<div id="modalClient" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-md overflow-hidden transform transition-all">
        <div class="bg-slate-900 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-base font-bold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-400 fill-current" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Klien Baru
            </h3>
            <button type="button" id="btnCloseClientModal" class="text-slate-400 hover:text-white transition cursor-pointer">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="formAddClient" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Nama Klien / Perusahaan <span class="text-red-500">*</span>
                </label>
                <input type="text" id="modal_nama_klien" required placeholder="Contoh: PT. Sumber Agung"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Detail Alamat / Kontak
                </label>
                <textarea id="modal_client_details" rows="3" placeholder="Alamat kantor, Attn, No Telepon..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email</label>
                    <input type="email" id="modal_email" placeholder="klien@company.com"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Telepon</label>
                    <input type="text" id="modal_telepon" placeholder="08123456789"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" id="btnCancelClientModal"
                    style="background-color: #f1f5f9 !important; color: #475569 !important;"
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" id="btnSaveClientModal"
                    style="background-color: #059669 !important; color: #ffffff !important;"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-emerald-600/20 flex items-center gap-2 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Simpan Klien</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Toast Notification --}}
<div id="toastNotification" class="fixed top-6 right-6 z-50 transform transition-all duration-300 -translate-y-20 opacity-0 pointer-events-none">
    <div class="bg-emerald-600 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 border border-emerald-500/50" style="background-color: #059669 !important; color: #ffffff !important;">
        <div class="p-1 bg-white/20 rounded-full flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white fill-current" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <p class="font-bold text-sm" id="toastMessage">Klien berhasil disimpan!</p>
        </div>
    </div>
</div>

<style>
    .ts-control {
        border-radius: 0.75rem !important;
        padding: 0.75rem 1rem !important;
        border-color: #cbd5e1 !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        color: #1e293b !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }
    .ts-wrapper.single .ts-control {
        background-color: #ffffff !important;
    }
    .ts-dropdown {
        border-radius: 0.75rem !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
        border-color: #cbd5e1 !important;
        font-size: 0.875rem !important;
    }
</style>

<script>
    // Data produk terdaftar dari backend
    const registeredProducts = @json($products);
    let clientsList = @json($clients);
    let rowCounter = 0;

    document.addEventListener('DOMContentLoaded', function() {
        // Modal Handlers
        const modalClient = document.getElementById('modalClient');
        const btnOpenClientModal = document.getElementById('btnOpenClientModal');
        const btnCloseClientModal = document.getElementById('btnCloseClientModal');
        const btnCancelClientModal = document.getElementById('btnCancelClientModal');
        const formAddClient = document.getElementById('formAddClient');

        const clientSelect = document.getElementById('client_select');
        const inputClientId = document.getElementById('client_id');
        const inputNamaKlien = document.getElementById('nama_klien');
        const txtClientDetails = document.getElementById('client_details');

        function openModal() { modalClient.classList.remove('hidden'); }
        function closeModal() {
            modalClient.classList.add('hidden');
            formAddClient.reset();
        }

        function showClientToast(msg) {
            const toast = document.getElementById('toastNotification');
            const toastMsg = document.getElementById('toastMessage');
            toastMsg.textContent = msg;

            toast.classList.remove('-translate-y-20', 'opacity-0', 'pointer-events-none');
            toast.classList.add('translate-y-0', 'opacity-100');

            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('-translate-y-20', 'opacity-0', 'pointer-events-none');
            }, 3500);
        }

        btnOpenClientModal.addEventListener('click', openModal);
        btnCloseClientModal.addEventListener('click', closeModal);
        btnCancelClientModal.addEventListener('click', closeModal);

        // Inisialisasi TomSelect agar Nama Klien Bisa Dicari Manual Secara Ketik
        let tomClient = null;
        if (window.TomSelect && clientSelect) {
            tomClient = new TomSelect('#client_select', {
                create: true,
                placeholder: '-- Ketik / Cari Nama Klien --',
                allowEmptyOption: true,
                onChange: function(value) {
                    if (!value) {
                        inputClientId.value = '';
                        inputNamaKlien.value = '';
                        txtClientDetails.value = '';
                        return;
                    }

                    const found = clientsList.find(c => c.id == value);
                    if (found) {
                        inputClientId.value = found.id;
                        inputNamaKlien.value = found.nama_klien;
                        txtClientDetails.value = found.client_details || found.alamat || '';
                    } else {
                        // User mengetik nama klien baru secara manual
                        inputClientId.value = '';
                        inputNamaKlien.value = value;
                    }
                }
            });
        }

        // Submit Add Client Modal via AJAX
        formAddClient.addEventListener('submit', function(e) {
            e.preventDefault();
            const nama = document.getElementById('modal_nama_klien').value.trim();
            const details = document.getElementById('modal_client_details').value.trim();
            const email = document.getElementById('modal_email').value.trim();
            const telepon = document.getElementById('modal_telepon').value.trim();

            if (!nama) return;

            fetch('{{ route("clients.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    nama_klien: nama,
                    client_details: details,
                    email: email,
                    telepon: telepon
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.client) {
                    const c = data.client;
                    clientsList.push(c);

                    if (tomClient) {
                        tomClient.addOption({
                            value: c.id,
                            text: c.nama_klien
                        });
                        tomClient.setValue(c.id);
                    } else {
                        const option = new Option(c.nama_klien, c.id, true, true);
                        option.dataset.nama = c.nama_klien;
                        option.dataset.details = c.client_details || '';
                        clientSelect.add(option);
                        clientSelect.value = c.id;
                    }

                    inputClientId.value = c.id;
                    inputNamaKlien.value = c.nama_klien;
                    txtClientDetails.value = c.client_details || '';

                    closeModal();

                    // Tampilkan Notifikasi Sukses
                    showClientToast(`Klien "${c.nama_klien}" berhasil disimpan!`);
                } else {
                    alert(data.message || 'Gagal menambah klien.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan data klien.');
            });
        });

        // Event change dropdown client fallback
        if (!tomClient && clientSelect) {
            clientSelect.addEventListener('change', function() {
                const selectedOpt = clientSelect.options[clientSelect.selectedIndex];
                if (selectedOpt && selectedOpt.value) {
                    inputClientId.value = selectedOpt.value;
                    inputNamaKlien.value = selectedOpt.dataset.nama || selectedOpt.text;
                    txtClientDetails.value = selectedOpt.dataset.details || '';
                } else {
                    inputClientId.value = '';
                    inputNamaKlien.value = '';
                    txtClientDetails.value = '';
                }
            });
        }

        // Sync initial selection if exists
        if (clientSelect && clientSelect.value && !tomClient) {
            const selectedOpt = clientSelect.options[clientSelect.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                inputClientId.value = selectedOpt.value;
                inputNamaKlien.value = selectedOpt.dataset.nama || selectedOpt.text;
                txtClientDetails.value = selectedOpt.dataset.details || '';
            }
        }

        // Dynamic Table Logic
        const tbody = document.getElementById('itemsTbody');
        const btnAddRow = document.getElementById('btnAddRow');
        const inputDiskonGlobal = document.getElementById('diskon_global');

        function createRow() {
            rowCounter++;
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50/70 transition-colors row-item';
            tr.dataset.rowId = rowCounter;

            // Product Select Options
            let prodOptions = `<option value="">-- Manual / Ketik Nama --</option>`;
            registeredProducts.forEach(p => {
                // Price base per L
                let basePrice = p.price_per_l || p.harga || 0;
                let packingSize = p.packing_size || '';
                prodOptions += `<option value="${p.id}" data-nama="${p.nama_produk}" data-packing="${packingSize}" data-price="${basePrice}">
                    ${p.nama_produk} (Base: Rp ${parseInt(basePrice).toLocaleString('id-ID')}/L)
                </option>`;
            });

            tr.innerHTML = `
                <td class="p-3 text-center font-bold text-slate-400 row-number">${tbody.children.length + 1}</td>

                {{-- Select Produk --}}
                <td class="p-3">
                    <select class="w-full p-2.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500 sel-product" name="items[${rowCounter}][product_id]">
                        ${prodOptions}
                    </select>
                    <input type="hidden" name="items[${rowCounter}][nama_produk]" class="inp-nama" value="">
                </td>

                {{-- Packing Size Dropdown --}}
                <td class="p-3 min-w-[120px]">
                    <select name="items[${rowCounter}][packing_size]"
                        class="w-full p-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 bg-white focus:ring-2 focus:ring-blue-500 sel-packing shadow-sm cursor-pointer"
                        style="color: #1e293b !important; background-color: #ffffff !important;">
                        <option value="1 L">1 L</option>
                        <option value="2.5 L">2.5 L</option>
                        <option value="5 L" selected>5 L</option>
                        <option value="8.75 L">8.75 L</option>
                        <option value="18 L">18 L</option>
                        <option value="20 L">20 L</option>
                        <option value="25 L">25 L</option>
                    </select>
                </td>

                {{-- Qty Order --}}
                <td class="p-3 min-w-[100px]">
                    <input type="number" min="1" step="any" name="items[${rowCounter}][qty_order]" value="1" placeholder="1" required
                        class="w-full p-2.5 rounded-xl border border-slate-300 text-xs text-center font-extrabold text-slate-900 bg-white focus:ring-2 focus:ring-blue-500 shadow-sm inp-qty"
                        style="color: #0f172a !important; background-color: #ffffff !important;">
                </td>

                {{-- Consumption (L) - Auto Computed --}}
                <td class="p-3 min-w-[110px]">
                    <input type="number" min="0" step="any" name="items[${rowCounter}][consumption_l]" value="5" readonly required
                        class="w-full p-2.5 rounded-xl border border-slate-200 text-xs text-right font-extrabold text-slate-800 bg-slate-100/80 focus:ring-2 focus:ring-blue-500 inp-consumption cursor-not-allowed"
                        style="color: #0f172a !important;">
                </td>

                {{-- Status Dropdown --}}
                <td class="p-3">
                    <select class="w-full p-2.5 rounded-lg border border-slate-300 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 sel-status" name="items[${rowCounter}][status_produk]">
                        <option value="READY" selected>READY</option>
                        <option value="2-7 PROSES">2-7 PROSES</option>
                        <option value="2-14 PROSES">2-14 PROSES</option>
                        <option value="OTHER">OTHER (KETIK MANUAL)</option>
                    </select>
                    <input type="text" name="items[${rowCounter}][status_other]" placeholder="Ketik status manual..."
                        class="w-full mt-1.5 p-2 rounded-lg border border-slate-300 text-xs text-slate-800 hidden inp-status-other">
                </td>

                {{-- Harga Normal / L --}}
                <td class="p-3">
                    <input type="number" min="0" step="any" name="items[${rowCounter}][base_price_per_liter]" value="0" placeholder="0"
                        class="w-full p-2.5 rounded-lg border border-slate-300 text-xs text-right font-bold text-slate-700 bg-slate-50 focus:ring-2 focus:ring-blue-500 inp-base-price">
                </td>

                {{-- Up (%) --}}
                <td class="p-3">
                    <input type="number" min="0" max="1000" step="any" name="items[${rowCounter}][up_percent]" value="40" placeholder="40"
                        class="w-full p-2.5 rounded-lg border border-slate-300 text-xs text-center font-extrabold text-blue-800 bg-blue-50/50 focus:ring-2 focus:ring-blue-500 inp-up-percent">
                </td>

                {{-- Price per Liter (+Up %) --}}
                <td class="p-3">
                    <input type="number" min="0" step="any" name="items[${rowCounter}][price_per_liter]" value="0" placeholder="0" required
                        class="w-full p-2.5 rounded-lg border border-blue-400 text-xs text-right font-extrabold text-blue-900 bg-blue-50 focus:ring-2 focus:ring-blue-500 inp-price">
                </td>

                {{-- Subtotal --}}
                <td class="p-3 text-right">
                    <span class="text-xs font-extrabold text-slate-800 txt-subtotal">Rp 0</span>
                </td>

                {{-- Action Delete --}}
                <td class="p-3 text-center">
                    <button type="button" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition btn-delete-row">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </td>
            `;

            tbody.appendChild(tr);

            // Row Event Listeners
            const selProduct = tr.querySelector('.sel-product');
            const inpNama = tr.querySelector('.inp-nama');
            const selPacking = tr.querySelector('.sel-packing');
            const inpQty = tr.querySelector('.inp-qty');
            const inpConsumption = tr.querySelector('.inp-consumption');
            const selStatus = tr.querySelector('.sel-status');
            const inpStatusOther = tr.querySelector('.inp-status-other');
            const inpBasePrice = tr.querySelector('.inp-base-price');
            const inpUpPercent = tr.querySelector('.inp-up-percent');
            const inpPrice = tr.querySelector('.inp-price');
            const btnDelete = tr.querySelector('.btn-delete-row');

            // Product Select Change Listener (Auto fill Base Price & Calculate Up Price)
            selProduct.addEventListener('change', function() {
                const opt = selProduct.options[selProduct.selectedIndex];
                if (opt.value) {
                    const prodObj = registeredProducts.find(p => p.id == opt.value);
                    inpNama.value = opt.dataset.nama || opt.text;

                    updatePackingOptions(tr, prodObj);

                    let basePrice = parseFloat(opt.dataset.price || 0);
                    inpBasePrice.value = basePrice;

                    let upPct = parseFloat(inpUpPercent.value) || 40;
                    let markedUpPrice = Math.round(basePrice * (1 + (upPct / 100)));
                    inpPrice.value = markedUpPrice;
                } else {
                    updatePackingOptions(tr, null);
                    inpBasePrice.value = 0;
                    inpPrice.value = 0;
                    inpNama.value = '';
                }
                recalculateRow(tr);
            });

            // Base price change listener
            if (inpBasePrice) {
                inpBasePrice.addEventListener('input', function() {
                    let basePrice = parseFloat(inpBasePrice.value) || 0;
                    let upPct = parseFloat(inpUpPercent.value) || 0;
                    inpPrice.value = Math.round(basePrice * (1 + (upPct / 100)));
                    recalculateRow(tr);
                });
            }

            // Up percent change listener
            if (inpUpPercent) {
                inpUpPercent.addEventListener('input', function() {
                    let basePrice = parseFloat(inpBasePrice.value) || 0;
                    let upPct = parseFloat(inpUpPercent.value) || 0;
                    inpPrice.value = Math.round(basePrice * (1 + (upPct / 100)));
                    recalculateRow(tr);
                });
            }

            // Price after Up change listener (manual edit of marked up price recalculates Up %)
            if (inpPrice) {
                inpPrice.addEventListener('input', function() {
                    let basePrice = parseFloat(inpBasePrice.value) || 0;
                    let finalPrice = parseFloat(inpPrice.value) || 0;
                    if (basePrice > 0) {
                        let pct = Math.round(((finalPrice / basePrice) - 1) * 100 * 100) / 100;
                        inpUpPercent.value = pct;
                    }
                    recalculateRow(tr);
                });
            }

            // Status Change Listener (Show manual text input when OTHER)
            selStatus.addEventListener('change', function() {
                if (selStatus.value === 'OTHER') {
                    inpStatusOther.classList.remove('hidden');
                    inpStatusOther.focus();
                } else {
                    inpStatusOther.classList.add('hidden');
                    inpStatusOther.value = '';
                }
            });

            // Packing size, Qty, Consumption change listeners
            [selPacking, inpQty].forEach(input => {
                if (input) {
                    input.addEventListener('change', function() { recalculateRow(tr); });
                    input.addEventListener('input', function() { recalculateRow(tr); });
                }
            });

            // Delete Row Listener
            btnDelete.addEventListener('click', function() {
                if (tbody.children.length > 1) {
                    tr.remove();
                    updateRowNumbers();
                    calculateTotals();
                } else {
                    alert('Minimal harus ada 1 baris produk!');
                }
            });

            updateRowNumbers();
            recalculateRow(tr);
        }

        const defaultSizes = ['1 L', '2.5 L', '5 L', '8.75 L', '18 L', '20 L', '25 L'];

        function getAvailablePackingSizes(prod) {
            if (!prod) return [];
            let sizes = [];

            if (prod.packings && Array.isArray(prod.packings) && prod.packings.length > 0) {
                prod.packings.forEach(pk => {
                    if (pk.packing_size && pk.packing_size.trim()) {
                        sizes.push(pk.packing_size.trim());
                    }
                });
            }

            if (prod.packing_size && prod.packing_size.trim()) {
                let parts = prod.packing_size.split(/[,;\/]+/);
                parts.forEach(p => {
                    let t = p.trim();
                    if (t && !sizes.includes(t)) {
                        sizes.push(t);
                    }
                });
            }

            let result = [];
            sizes.forEach(s => {
                let upper = s.toUpperCase();
                let formatted = upper.includes('L') ? s : s + ' L';
                if (!result.includes(formatted)) {
                    result.push(formatted);
                }
            });

            return result;
        }

        function updatePackingOptions(tr, prod, selectedSize = null) {
            const selPacking = tr.querySelector('.sel-packing');
            if (!selPacking) return;

            let availableSizes = getAvailablePackingSizes(prod);
            let sizesToUse = (availableSizes && availableSizes.length > 0) ? availableSizes : defaultSizes;

            let currentVal = selectedSize || selPacking.value;
            selPacking.innerHTML = '';

            sizesToUse.forEach(size => {
                let opt = new Option(size, size);
                selPacking.add(opt);
            });

            if (currentVal) {
                let matched = false;
                for (let i = 0; i < selPacking.options.length; i++) {
                    if (selPacking.options[i].value.toLowerCase() === currentVal.toLowerCase()) {
                        selPacking.selectedIndex = i;
                        matched = true;
                        break;
                    }
                }
                if (!matched && sizesToUse === defaultSizes) {
                    let customOpt = new Option(currentVal, currentVal, true, true);
                    selPacking.add(customOpt);
                }
            }
        }

        function getNumericPackingSize(str) {
            if (!str) return 1;
            let match = str.toString().match(/([0-9]+(\.[0-9]+)?)/);
            return match ? parseFloat(match[1]) : 1;
        }

        function recalculateRow(tr) {
            const selPacking = tr.querySelector('.sel-packing');
            const inpQty = tr.querySelector('.inp-qty');
            const inpConsumption = tr.querySelector('.inp-consumption');
            const inpPrice = tr.querySelector('.inp-price');

            const packingNum = getNumericPackingSize(selPacking ? selPacking.value : '1');
            const qty = parseFloat(inpQty ? inpQty.value : 1) || 0;

            // Consumption (L) = Packing Size * Qty Order
            const consumption = Math.round((packingNum * qty) * 100) / 100;
            if (inpConsumption) {
                inpConsumption.value = consumption;
            }

            const price = parseFloat(inpPrice ? inpPrice.value : 0) || 0;
            const subtotal = Math.round(consumption * price);

            const txtSubtotal = tr.querySelector('.txt-subtotal');
            if (txtSubtotal) {
                txtSubtotal.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            }
            tr.dataset.subtotal = subtotal;

            calculateTotals();
        }

        function updateRowNumbers() {
            Array.from(tbody.children).forEach((tr, idx) => {
                const numEl = tr.querySelector('.row-number');
                if (numEl) numEl.textContent = idx + 1;
            });
        }

        function calculateTotals() {
            let total = 0;
            Array.from(tbody.children).forEach(tr => {
                const sub = parseFloat(tr.dataset.subtotal || 0);
                total += sub;
            });

            const diskon = parseFloat(inputDiskonGlobal.value) || 0;
            const grandTotal = Math.max(0, total - diskon);

            document.getElementById('txtSubtotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('txtGrandTotal').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        if (inputDiskonGlobal) {
            inputDiskonGlobal.addEventListener('input', calculateTotals);
            inputDiskonGlobal.addEventListener('change', calculateTotals);
        }

        if (btnAddRow) {
            btnAddRow.addEventListener('click', function() {
                createRow();
            });
        }

        // Validation before submitting form
        const quotationForm = document.getElementById('quotationForm');
        if (quotationForm) {
            quotationForm.addEventListener('submit', function(e) {
                if (!inputNamaKlien.value || !inputNamaKlien.value.trim()) {
                    e.preventDefault();
                    alert('Silakan pilih Klien / Perusahaan terlebih dahulu!');
                    clientSelect.focus();
                    return false;
                }
            });
        }

        // Init with 1 default row
        createRow();
    });
</script>
@endsection
