@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-md border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Tambah Data Produk Baru</h1>
            <a href="{{ route('harga.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('harga.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Produk (Span 2) --}}
                <div class="md:col-span-2">
                    <label for="nama_produk" class="block text-sm font-semibold text-gray-700">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk') }}"
                           placeholder="Contoh: Jotashield Antifade Colour"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800 text-sm" required>
                </div>

                {{-- Comp B (Optional) --}}
                <div>
                    <label for="comp_b" class="block text-sm font-semibold text-gray-700">Comp B <span class="text-xs text-gray-500 font-normal">(Optional)</span></label>
                    <input type="text" name="comp_b" id="comp_b" value="{{ old('comp_b') }}"
                           placeholder="Contoh: Hardener Comp B / Standard Comp B (opsional)"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800 text-sm">
                </div>

                {{-- Packing Size Comp B (Optional) --}}
                <div>
                    <label for="packing_size_b" class="block text-sm font-semibold text-gray-700">Packing Size Comp B <span class="text-xs text-gray-500 font-normal">(Optional)</span></label>
                    <input type="text" name="packing_size_b" id="packing_size_b" value="{{ old('packing_size_b') }}"
                           placeholder="Contoh: 5 L, 2.5 L, 0.5 L"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800 text-sm">
                </div>

                {{-- Generic (Optional) --}}
                <div>
                    <label for="generic" class="block text-sm font-semibold text-gray-700">Generic <span class="text-xs text-gray-500 font-normal">(Optional)</span></label>
                    <input type="text" name="generic" id="generic" value="{{ old('generic') }}"
                           placeholder="Contoh: Pure Acrylic, Epoxy, dll."
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800 text-sm">
                </div>

                {{-- Primer / Topcoat (Optional Dropdown) --}}
                <div>
                    <label for="primer_topcoat" class="block text-sm font-semibold text-gray-700">Primer / Topcoat <span class="text-xs text-gray-500 font-normal">(Optional)</span></label>
                    <select name="primer_topcoat" id="primer_topcoat"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800 text-sm">
                        <option value="">-- Pilih Primer / Topcoat --</option>
                        <option value="Primer" {{ old('primer_topcoat') == 'Primer' ? 'selected' : '' }}>Primer</option>
                        <option value="Topcoat" {{ old('primer_topcoat') == 'Topcoat' ? 'selected' : '' }}>Topcoat</option>
                    </select>
                </div>

                {{-- Category (Optional Dropdown) --}}
                <div>
                    <label for="category" class="block text-sm font-semibold text-gray-700">Category <span class="text-xs text-gray-500 font-normal">(Optional)</span></label>
                    <select name="category" id="category"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800 text-sm">
                        <option value="">-- Pilih Category --</option>
                        <option value="Marine" {{ old('category') == 'Marine' ? 'selected' : '' }}>Marine</option>
                        <option value="Marine & PC" {{ old('category') == 'Marine & PC' ? 'selected' : '' }}>Marine & PC</option>
                        <option value="PC - Floor Coating" {{ old('category') == 'PC - Floor Coating' ? 'selected' : '' }}>PC - Floor Coating</option>
                    </select>
                </div>

                {{-- Thinner (Optional) --}}
                <div>
                    <label for="thinner" class="block text-sm font-semibold text-gray-700">Thinner <span class="text-xs text-gray-500 font-normal">(Optional)</span></label>
                    <input type="text" name="thinner" id="thinner" value="{{ old('thinner') }}"
                           placeholder="Contoh: Thinner No. 7 / No. 17"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800 text-sm">
                </div>

                {{-- Price per (L) (Span 2) --}}
                <div class="md:col-span-2">
                    <label for="price_per_l" class="block text-sm font-semibold text-gray-700">Price per (L) (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price_per_l" id="price_per_l" value="{{ old('price_per_l') }}"
                           placeholder="Contoh: 150000"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800 text-sm" min="0" required>
                </div>
            </div>

            {{-- Packing Size (Multiple Dynamic) --}}
            <div class="border-t border-gray-200 pt-5 mt-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        Packing Size (L) <span class="text-xs text-gray-500 font-normal">(Optional, bisa lebih dari satu)</span>
                    </label>
                    <button type="button" id="add-packing-btn"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-semibold py-1.5 px-3 rounded border border-gray-300 transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        + Tambah Packing Size
                    </button>
                </div>

                <div id="packing-container" class="space-y-2.5">
                    <div class="packing-row flex items-center gap-2">
                        <input type="text" name="packing_sizes[]" placeholder="Contoh: 2.5 L atau 20 L"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800 text-sm">
                        <button type="button" class="remove-packing-btn text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded transition" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Batch Number (Multiple Dynamic) --}}
            <div class="border-t border-gray-200 pt-5 mt-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        Batch Number <span class="text-xs text-gray-500 font-normal">(Optional, bisa lebih dari satu)</span>
                    </label>
                    <button type="button" id="add-batch-btn"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-semibold py-1.5 px-3 rounded border border-gray-300 transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        + Tambah Batch Number
                    </button>
                </div>

                <div id="batch-container" class="space-y-2.5">
                    <div class="batch-row flex items-center gap-2">
                        <input type="text" name="batch_numbers[]" placeholder="Contoh: BATCH-2026-001"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800 text-sm">
                        <button type="button" class="remove-batch-btn text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded transition" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <button type="submit" class="w-full bg-gray-800 text-white font-bold py-3 px-6 rounded-lg hover:bg-gray-700 transition ease-in-out duration-150 shadow">
                    Simpan Data Produk
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- LOGIKA PACKING SIZE ---
        const packingContainer = document.getElementById('packing-container');
        const addPackingBtn = document.getElementById('add-packing-btn');

        addPackingBtn.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'packing-row flex items-center gap-2 mt-2';
            row.innerHTML = `
                <input type="text" name="packing_sizes[]" placeholder="Contoh: 5 L atau 20 L"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800 text-sm">
                <button type="button" class="remove-packing-btn text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded transition" title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
            packingContainer.appendChild(row);
            attachRemovePackingEvent(row.querySelector('.remove-packing-btn'));
        });

        function attachRemovePackingEvent(btn) {
            btn.addEventListener('click', function() {
                const rows = packingContainer.querySelectorAll('.packing-row');
                if (rows.length > 1) {
                    btn.closest('.packing-row').remove();
                } else {
                    const input = btn.closest('.packing-row').querySelector('input');
                    if (input) input.value = '';
                }
            });
        }
        document.querySelectorAll('.remove-packing-btn').forEach(attachRemovePackingEvent);

        // --- LOGIKA BATCH NUMBER ---
        const batchContainer = document.getElementById('batch-container');
        const addBatchBtn = document.getElementById('add-batch-btn');

        addBatchBtn.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'batch-row flex items-center gap-2 mt-2';
            row.innerHTML = `
                <input type="text" name="batch_numbers[]" placeholder="Contoh: BATCH-2026-002"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800 text-sm">
                <button type="button" class="remove-batch-btn text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded transition" title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
            batchContainer.appendChild(row);
            attachRemoveBatchEvent(row.querySelector('.remove-batch-btn'));
        });

        function attachRemoveBatchEvent(btn) {
            btn.addEventListener('click', function() {
                const rows = batchContainer.querySelectorAll('.batch-row');
                if (rows.length > 1) {
                    btn.closest('.batch-row').remove();
                } else {
                    const input = btn.closest('.batch-row').querySelector('input');
                    if (input) input.value = '';
                }
            });
        }
        document.querySelectorAll('.remove-batch-btn').forEach(attachRemoveBatchEvent);
    });
</script>
@endsection