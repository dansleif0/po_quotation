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
            Kembali ke Histori
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
                            <select id="client_select" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-slate-800 appearance-none bg-white pr-10">
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
                            class="px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-md shadow-emerald-600/20 flex items-center justify-center gap-1.5 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span>Tambah</span>
                        </button>
                    </div>

                    {{-- Input Hidden & Text Nama Klien (bisa manual jika belum pilih) --}}
                    <input type="hidden" name="client_id" id="client_id">
                    <input type="text" name="nama_klien" id="nama_klien" required placeholder="Atau ketik nama klien secara manual..."
                        class="mt-2.5 w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-slate-800">
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
                    <p class="text-xs text-amber-600 font-semibold mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Note: Price per Liter otomatis di-up +40% dari harga dasar produk terdaftar.
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
                            <th class="p-3 rounded-l-xl w-10 text-center">#</th>
                            <th class="p-3 min-w-[220px]">Pilih Produk</th>
                            <th class="p-3 min-w-[180px]">Nama Produk</th>
                            <th class="p-3 w-28">Packing Size</th>
                            <th class="p-3 w-24 text-right">Qty Order</th>
                            <th class="p-3 w-28 text-right">Consumption (L)</th>
                            <th class="p-3 min-w-[160px]">Status</th>
                            <th class="p-3 min-w-[150px] text-right">Price / L (+40%)</th>
                            <th class="p-3 min-w-[150px] text-right">Subtotal (Rp)</th>
                            <th class="p-3 rounded-r-xl w-12 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTbody" class="divide-y divide-slate-100">
                        {{-- Baris akan dibuat secara dinamis oleh JavaScript --}}
                    </tbody>
                </table>
            </div>

            {{-- Summary & Diskon --}}
            <div class="mt-8 pt-6 border-t border-slate-200/80 flex flex-col md:flex-row justify-end items-end gap-6">
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
            <a href="{{ route('histori.index') }}" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-sm transition-all">
                Batal
            </a>
            <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl text-sm transition-all shadow-xl shadow-blue-500/25 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan & Terbitkan Quotation
            </button>
        </div>
    </form>
</div>

{{-- MODAL: Tambah Klien Baru --}}
<div id="modalClient" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-md overflow-hidden transform transition-all">
        <div class="bg-slate-900 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-base font-bold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Klien Baru
            </h3>
            <button type="button" id="btnCloseClientModal" class="text-slate-400 hover:text-white transition">
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
                <button type="button" id="btnCancelClientModal" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-sm transition">
                    Batal
                </button>
                <button type="submit" id="btnSaveClientModal" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-emerald-600/20">
                    Simpan Klien
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Data produk terdaftar dari backend
    const registeredProducts = @json($products);
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

        btnOpenClientModal.addEventListener('click', openModal);
        btnCloseClientModal.addEventListener('click', closeModal);
        btnCancelClientModal.addEventListener('click', closeModal);

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
                    const option = new Option(c.nama_klien, c.id, true, true);
                    option.dataset.nama = c.nama_klien;
                    option.dataset.details = c.client_details || '';
                    clientSelect.add(option);

                    inputClientId.value = c.id;
                    inputNamaKlien.value = c.nama_klien;
                    txtClientDetails.value = c.client_details || '';
                    
                    closeModal();
                } else {
                    alert('Gagal menambah klien.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan data klien.');
            });
        });

        // Event change dropdown client
        clientSelect.addEventListener('change', function() {
            const selectedOpt = clientSelect.options[clientSelect.selectedIndex];
            if (selectedOpt.value) {
                inputClientId.value = selectedOpt.value;
                inputNamaKlien.value = selectedOpt.dataset.nama || selectedOpt.text;
                txtClientDetails.value = selectedOpt.dataset.details || '';
            } else {
                inputClientId.value = '';
            }
        });

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
                </td>

                {{-- Nama Produk --}}
                <td class="p-3">
                    <input type="text" name="items[${rowCounter}][nama_produk]" required placeholder="Nama produk..."
                        class="w-full p-2.5 rounded-lg border border-slate-300 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500 inp-nama">
                </td>

                {{-- Packing Size --}}
                <td class="p-3">
                    <input type="text" name="items[${rowCounter}][packing_size]" placeholder="Misal: 5 L / 20 L"
                        class="w-full p-2.5 rounded-lg border border-slate-300 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500 inp-packing">
                </td>

                {{-- Qty Order --}}
                <td class="p-3">
                    <input type="number" min="0" step="any" name="items[${rowCounter}][qty_order]" value="1" required
                        class="w-full p-2.5 rounded-lg border border-slate-300 text-xs text-right font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 inp-qty">
                </td>

                {{-- Consumption (L) --}}
                <td class="p-3">
                    <input type="number" min="0" step="any" name="items[${rowCounter}][consumption_l]" value="1" required
                        class="w-full p-2.5 rounded-lg border border-slate-300 text-xs text-right font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 inp-consumption">
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

                {{-- Price per Liter (+40%) --}}
                <td class="p-3">
                    <input type="hidden" name="items[${rowCounter}][base_price_per_liter]" class="inp-base-price" value="0">
                    <input type="number" min="0" step="any" name="items[${rowCounter}][price_per_liter]" value="0" required
                        class="w-full p-2.5 rounded-lg border border-slate-300 text-xs text-right font-bold text-blue-700 bg-blue-50/40 focus:ring-2 focus:ring-blue-500 inp-price">
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
            const inpPacking = tr.querySelector('.inp-packing');
            const inpQty = tr.querySelector('.inp-qty');
            const inpConsumption = tr.querySelector('.inp-consumption');
            const selStatus = tr.querySelector('.sel-status');
            const inpStatusOther = tr.querySelector('.inp-status-other');
            const inpBasePrice = tr.querySelector('.inp-base-price');
            const inpPrice = tr.querySelector('.inp-price');
            const btnDelete = tr.querySelector('.btn-delete-row');

            // Product Select Change Listener (+40% Markup Rule)
            selProduct.addEventListener('change', function() {
                const opt = selProduct.options[selProduct.selectedIndex];
                if (opt.value) {
                    inpNama.value = opt.dataset.nama || '';
                    inpPacking.value = opt.dataset.packing || '';
                    let basePrice = parseFloat(opt.dataset.price || 0);
                    inpBasePrice.value = basePrice;

                    // Calculate +40% Up Price
                    let markedUpPrice = Math.round(basePrice * 1.40);
                    inpPrice.value = markedUpPrice;
                } else {
                    inpBasePrice.value = 0;
                }
                recalculateRow(tr);
            });

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

            // Quantity & Price change listeners
            [inpQty, inpConsumption, inpPrice].forEach(input => {
                input.addEventListener('input', function() {
                    recalculateRow(tr);
                });
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
            calculateTotals();
        }

        function recalculateRow(tr) {
            const consumption = parseFloat(tr.querySelector('.inp-consumption').value) || 0;
            const price = parseFloat(tr.querySelector('.inp-price').value) || 0;
            const subtotal = Math.round(consumption * price);

            tr.querySelector('.txt-subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
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

        inputDiskonGlobal.addEventListener('input', calculateTotals);
        btnAddRow.addEventListener('click', createRow);

        // Init with 1 default row
        createRow();
    });
</script>
@endsection
