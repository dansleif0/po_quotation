@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Top Header --}}
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Purchase Order (PO) - {{ $po->po_number }}</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui data Purchase Order resmi PT Tasniem Gerai Inspirasi.</p>
        </div>
        <a href="{{ route('po.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition">
            &larr; Kembali
        </a>
    </div>

    {{-- Form Container --}}
    <form action="{{ route('po.update', $po->id) }}" method="POST" id="formEditPO" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Card 1: Header Info & Supplier --}}
        <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-200/80 space-y-6">
            <h2 class="text-base font-bold text-slate-800 border-b pb-3 uppercase tracking-wider text-blue-700 flex items-center gap-2">
                <span>📋 Informasi Dokumen PO & Supplier</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- No. PO --}}
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                        PURCHASE ORDER NO. <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="po_number" id="po_number" value="{{ old('po_number', $po->po_number) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 font-mono font-extrabold text-blue-700 text-sm bg-slate-50/50">
                </div>

                {{-- Referensi Quotation --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                        Referensi Quotation (Penawaran)
                    </label>
                    <select name="offer_id" id="offer_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-slate-800">
                        <option value="">-- Manual (Tanpa Quotation) --</option>
                        @foreach($offers as $off)
                            <option value="{{ $off->id }}"
                                data-klien="{{ $off->nama_klien }}"
                                data-details="{{ $off->client_details }}"
                                data-no="{{ $off->no_surat }}"
                                data-project="{{ $off->project_no ?: $off->no_surat }}"
                                {{ (old('offer_id', $po->offer_id) == $off->id) ? 'selected' : '' }}>
                                {{ $off->no_surat ?: ('Quotation #' . $off->id) }} - {{ $off->nama_klien }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal PO --}}
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                        DATE (TANGGAL PO) <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_po" id="tanggal_po" value="{{ old('tanggal_po', \Carbon\Carbon::parse($po->tanggal_po)->format('Y-m-d')) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-slate-800">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Box SUPPLIER --}}
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-3">
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700">
                        SUPPLIER (Pemasok / Klien) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="supplier_name" id="supplier_name" value="{{ old('supplier_name', $po->supplier_name ?: 'PT CIPTA MARITIM PERKASA') }}" required placeholder="Nama Supplier..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-bold text-sm text-slate-900 bg-white">
                    <textarea name="supplier_address" id="supplier_address" rows="2" placeholder="Alamat Supplier..."
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 bg-white">{{ old('supplier_address', $po->supplier_address ?: 'Ruko Tunas Regency Blok A5 No 09 – 10 Tanjung Uncang') }}</textarea>
                </div>

                {{-- Box DELIVER TO --}}
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-3">
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700">
                        DELIVER TO (Tujuan Pengiriman) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="deliver_to_name" id="deliver_to_name" value="{{ old('deliver_to_name', $po->deliver_to_name ?: 'PT TASNIEM GERAI INSPIRASI') }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-bold text-sm text-slate-900 bg-white">
                    <textarea name="deliver_to_address" id="deliver_to_address" rows="2"
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 bg-white">{{ old('deliver_to_address', $po->deliver_to_address ?: 'Komp. Ruko KDA Junction Blok C 8-9') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 pt-2">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">CURRENCY</label>
                    <input type="text" name="currency" value="{{ old('currency', $po->currency ?: 'IDR') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 font-bold text-xs text-center">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">DELIVERY DATE</label>
                    <input type="text" name="delivery_date" value="{{ old('delivery_date', $po->delivery_date ?: '-') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-center">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">OFFER LETTER / REF</label>
                    <input type="text" name="offer_letter" id="offer_letter" value="{{ old('offer_letter', $po->offer_letter ?: ($po->offer->no_surat ?? '')) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">PAYMENT TERM</label>
                    <input type="text" name="payment_term" value="{{ old('payment_term', $po->payment_term ?: 'BANK TRANSFER') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">STATUS PO</label>
                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold bg-white text-slate-800">
                        <option value="TERBIT" {{ old('status', $po->status) == 'TERBIT' ? 'selected' : '' }}>TERBIT</option>
                        <option value="PROSES" {{ old('status', $po->status) == 'PROSES' ? 'selected' : '' }}>PROSES</option>
                        <option value="SELESAI" {{ old('status', $po->status) == 'SELESAI' ? 'selected' : '' }}>SELESAI</option>
                        <option value="BATAL" {{ old('status', $po->status) == 'BATAL' ? 'selected' : '' }}>BATAL</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">JOB PROJECT</label>
                <input type="text" name="job_project" id="job_project" value="{{ old('job_project', $po->job_project ?: ($po->offer->project_no ?? ($po->offer->no_surat ?? ''))) }}"
                    placeholder="Sama dengan Quotation (Project No / No Surat)..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-bold text-slate-800">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">ISSUED BY</label>
                    <input type="text" name="issued_by" value="{{ old('issued_by', $po->issued_by ?: 'Ardian Wijaya Kusuma') }}"
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">APPROVED BY</label>
                    <input type="text" name="approved_by" value="{{ old('approved_by', $po->approved_by ?: 'Samsu Rizal') }}"
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>
            </div>

            <input type="hidden" name="nama_klien" id="nama_klien" value="{{ old('nama_klien', $po->nama_klien) }}">
            <input type="hidden" name="client_details" id="client_details" value="{{ old('client_details', $po->client_details) }}">
        </div>

        {{-- Card 2: Rincian Produk PO --}}
        <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-200/80 space-y-6">
            <div class="flex items-center justify-between border-b pb-3">
                <h2 class="text-base font-bold text-slate-800 uppercase tracking-wider text-blue-700 flex items-center gap-2">
                    <span>📦 Rincian Produk Purchase Order</span>
                </h2>
                <button type="button" id="btnAddRow" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5 cursor-pointer">
                    <span>+ Tambah Produk</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 uppercase text-[11px] font-bold">
                            <th class="p-3 w-10 text-center rounded-l-xl">#</th>
                            <th class="p-3 min-w-[220px]">Product Name</th>
                            <th class="p-3 w-28 text-center">Packing Size (L)</th>
                            <th class="p-3 w-24 text-center">Qty Order</th>
                            <th class="p-3 w-28 text-right">Consumption (L)</th>
                            <th class="p-3 min-w-[140px] text-right">Price per (L)</th>
                            <th class="p-3 min-w-[150px] text-right">Total Price (Rp)</th>
                            <th class="p-3 w-12 text-center rounded-r-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="poItemsTbody" class="divide-y divide-slate-100">
                        {{-- Rows generated dynamically --}}
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-200">
                <div class="w-full md:w-80 bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2 text-right">
                    <div class="text-xs font-bold text-slate-500 uppercase">Total Nilai PO:</div>
                    <div class="text-2xl font-extrabold text-blue-700" id="txtGrandTotal">Rp {{ number_format($po->total_nilai, 0, ',', '.') }}</div>
                    <input type="hidden" name="total_nilai" id="total_nilai" value="{{ $po->total_nilai }}">
                </div>
            </div>
        </div>

        {{-- Form Submit Actions --}}
        <div class="flex items-center justify-end gap-4 mb-12">
            <a href="{{ route('po.index') }}" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition">
                Batal
            </a>
            <button type="submit"
                style="background-color: #2563eb !important; color: #ffffff !important;"
                class="px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition shadow-xl shadow-blue-500/25 flex items-center gap-2 cursor-pointer">
                <svg class="w-5 h-5 fill-current text-white" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="font-bold text-white tracking-wide">Simpan Perubahan PO</span>
            </button>
        </div>
    </form>
</div>

<script>
    const registeredProducts = @json($products);
    const initialPoItems = @json($po->items);
    let rowCounter = 0;

    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('poItemsTbody');
        const btnAddRow = document.getElementById('btnAddRow');
        const offerSelect = document.getElementById('offer_id');

        function getNumericPackingSize(str) {
            if (!str) return 1;
            let match = str.toString().match(/([0-9]+(\.[0-9]+)?)/);
            return match ? parseFloat(match[1]) : 1;
        }

        function createRow(initialData = null) {
            rowCounter++;
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50/70 transition-colors row-item';

            let prodNameVal = initialData ? (initialData.nama_produk || '') : '';
            let packingVal = initialData ? (initialData.packing_size || '5 L') : '5 L';
            let qtyVal = initialData ? (initialData.qty_order || 1) : 1;
            let priceVal = initialData ? (initialData.price_per_liter || 0) : 0;

            if (initialData && initialData.nama_produk) {
                let foundProd = registeredProducts.find(p => p.nama_produk.toLowerCase() === initialData.nama_produk.toLowerCase());
                if (foundProd && (foundProd.price_per_l || foundProd.harga)) {
                    priceVal = Math.round(foundProd.price_per_l || foundProd.harga);
                }
            }
            let consumptionVal = initialData ? (initialData.consumption_l || (getNumericPackingSize(packingVal) * qtyVal)) : 5;

            let prodOptions = `<option value="">-- Ketik Manual --</option>`;
            registeredProducts.forEach(p => {
                let isSel = (p.nama_produk.toLowerCase() === prodNameVal.toLowerCase()) ? 'selected' : '';
                let basePrice = Math.round(p.price_per_l || p.harga || 0);
                prodOptions += `<option value="${p.id}" data-nama="${p.nama_produk}" data-packing="${p.packing_size || '5 L'}" data-price="${basePrice}" ${isSel}>
                    ${p.nama_produk} (Rp ${parseInt(basePrice).toLocaleString('id-ID')}/L)
                </option>`;
            });

            tr.innerHTML = `
                <td class="p-3 text-center font-bold text-slate-400 row-number">${tbody.children.length + 1}</td>
                <td class="p-3">
                    <select class="w-full p-2 rounded-lg border border-slate-300 text-xs font-semibold text-slate-800 mb-1 sel-prod-list">
                        ${prodOptions}
                    </select>
                    <input type="text" name="items[${rowCounter}][nama_produk]" value="${prodNameVal}" required placeholder="Ketikan nama produk..."
                        class="w-full p-2 rounded-lg border border-slate-300 text-xs font-bold text-slate-900 inp-nama">
                </td>
                <td class="p-3">
                    <select name="items[${rowCounter}][packing_size]" class="w-full p-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 sel-packing">
                        <option value="1 L" ${packingVal == '1 L' || packingVal == '1' ? 'selected' : ''}>1 L</option>
                        <option value="2.5 L" ${packingVal == '2.5 L' || packingVal == '2.5' ? 'selected' : ''}>2.5 L</option>
                        <option value="5 L" ${packingVal == '5 L' || packingVal == '5' ? 'selected' : ''}>5 L</option>
                        <option value="8.75 L" ${packingVal == '8.75 L' || packingVal == '8.75' ? 'selected' : ''}>8.75 L</option>
                        <option value="10.6 L" ${packingVal == '10.6 L' || packingVal == '10.6' ? 'selected' : ''}>10.6 L</option>
                        <option value="18 L" ${packingVal == '18 L' || packingVal == '18' ? 'selected' : ''}>18 L</option>
                        <option value="20 L" ${packingVal == '20 L' || packingVal == '20' ? 'selected' : ''}>20 L</option>
                        <option value="25 L" ${packingVal == '25 L' || packingVal == '25' ? 'selected' : ''}>25 L</option>
                    </select>
                </td>
                <td class="p-3">
                    <input type="number" min="1" step="any" name="items[${rowCounter}][qty_order]" value="${qtyVal}" required
                        class="w-full p-2 rounded-xl border border-slate-300 text-xs text-center font-extrabold text-slate-900 inp-qty">
                </td>
                <td class="p-3">
                    <input type="number" min="0" step="any" name="items[${rowCounter}][consumption_l]" value="${consumptionVal}" readonly required
                        class="w-full p-2 rounded-xl border border-slate-200 text-xs text-right font-extrabold text-slate-800 bg-slate-100/80 inp-consumption cursor-not-allowed">
                </td>
                <td class="p-3">
                    <input type="number" min="0" step="any" name="items[${rowCounter}][price_per_liter]" value="${priceVal}" required
                        class="w-full p-2 rounded-xl border border-slate-300 text-xs text-right font-bold text-blue-700 inp-price">
                </td>
                <td class="p-3 text-right">
                    <input type="hidden" name="items[${rowCounter}][total_price]" class="inp-total-price" value="0">
                    <span class="text-xs font-extrabold text-slate-900 txt-subtotal">Rp 0</span>
                </td>
                <td class="p-3 text-center">
                    <button type="button" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition btn-delete-row">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </td>
            `;

            tbody.appendChild(tr);

            const selProdList = tr.querySelector('.sel-prod-list');
            const inpNama = tr.querySelector('.inp-nama');
            const selPacking = tr.querySelector('.sel-packing');
            const inpQty = tr.querySelector('.inp-qty');
            const inpConsumption = tr.querySelector('.inp-consumption');
            const inpPrice = tr.querySelector('.inp-price');
            const btnDelete = tr.querySelector('.btn-delete-row');

            selProdList.addEventListener('change', function() {
                const opt = selProdList.options[selProdList.selectedIndex];
                if (opt.value) {
                    inpNama.value = opt.dataset.nama || '';
                    if (opt.dataset.price) inpPrice.value = opt.dataset.price;
                }
                recalculateRow(tr);
            });

            [selPacking, inpQty, inpPrice].forEach(input => {
                input.addEventListener('change', () => recalculateRow(tr));
                input.addEventListener('input', () => recalculateRow(tr));
            });

            btnDelete.addEventListener('click', function() {
                if (tbody.children.length > 1) {
                    tr.remove();
                    updateRowNumbers();
                    recalculateTotals();
                } else {
                    alert('Minimal harus ada 1 baris produk!');
                }
            });

            recalculateRow(tr);
            updateRowNumbers();
        }

        function recalculateRow(tr) {
            const selPacking = tr.querySelector('.sel-packing');
            const inpQty = tr.querySelector('.inp-qty');
            const inpConsumption = tr.querySelector('.inp-consumption');
            const inpPrice = tr.querySelector('.inp-price');
            const inpTotalPrice = tr.querySelector('.inp-total-price');
            const txtSubtotal = tr.querySelector('.txt-subtotal');

            const pNum = getNumericPackingSize(selPacking.value);
            const qty = parseFloat(inpQty.value) || 0;
            const consumption = Math.round((pNum * qty) * 100) / 100;
            inpConsumption.value = consumption;

            const price = parseFloat(inpPrice.value) || 0;
            const rowTotal = Math.round(consumption * price);

            inpTotalPrice.value = rowTotal;
            txtSubtotal.textContent = 'Rp ' + rowTotal.toLocaleString('id-ID');
            tr.dataset.subtotal = rowTotal;

            recalculateTotals();
        }

        function updateRowNumbers() {
            Array.from(tbody.children).forEach((tr, idx) => {
                const numEl = tr.querySelector('.row-number');
                if (numEl) numEl.textContent = idx + 1;
            });
        }

        function recalculateTotals() {
            let total = 0;
            Array.from(tbody.children).forEach(tr => {
                total += parseFloat(tr.dataset.subtotal || 0);
            });
            document.getElementById('txtGrandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('total_nilai').value = total;
        }

        btnAddRow.addEventListener('click', () => createRow());

        // Initialize existing PO rows
        if (initialPoItems && initialPoItems.length > 0) {
            initialPoItems.forEach(item => createRow(item));
        } else {
            createRow();
        }

        // Offer Select listener
        offerSelect.addEventListener('change', function() {
            const opt = offerSelect.options[offerSelect.selectedIndex];
            if (opt.value) {
                if (opt.dataset.klien) document.getElementById('nama_klien').value = opt.dataset.klien;
                if (opt.dataset.no) document.getElementById('offer_letter').value = opt.dataset.no;
                let proj = opt.dataset.project || opt.dataset.no || '';
                document.getElementById('job_project').value = proj;
            }
        });
    });
</script>
@endsection
