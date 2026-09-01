@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-7xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Histori Invoice
            </h1>
            <div class="flex gap-2">
                <button type="submit" form="soaForm" id="buatSoaBtn" disabled class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Buat SOA
                </button>
                <a href="{{ route('invoice.create') }}" class="bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700 transition shadow-sm">
                    + Buat Invoice Baru
                </a>
            </div>
        </div>

        <form action="{{ route('invoice.histori') }}" method="GET" class="mb-6">
            <div class="flex gap-2">
                <input type="text"
                       name="search"
                       placeholder="Cari No. Invoice, Nama Klien, atau No. Surat Penawaran..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800"
                       value="{{ $search ?? '' }}">
                <button type="submit" class="mt-1 bg-gray-800 text-white font-bold py-2 px-6 rounded hover:bg-gray-700 transition">
                    Cari
                </button>
            </div>
        </form>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow-sm" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- PERBAIKAN: Hapus overflow-hidden agar dropdown tidak terpotong --}}
        {{-- Jika tabel butuh scroll horizontal, pindahkan overflow ke div dalam --}}
        <form action="{{ route('soa.create') }}" method="GET" id="soaForm">
            <div class="bg-white shadow-md rounded-lg border border-gray-200 relative">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-white uppercase bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 rounded-tl-lg">Tanggal Invoice</th>
                            <th scope="col" class="px-6 py-3">No. Invoice</th>
                            <th scope="col" class="px-6 py-3">Nama Klien</th>
                            <th scope="col" class="px-6 py-3">No. Surat Penawaran</th>
                            <th scope="col" class="px-6 py-3 text-center">File PO Supplier</th>
                            <th scope="col" class="px-6 py-3 text-right">Total Tagihan</th>
                            <th scope="col" class="px-6 py-3 text-center">Status</th>
                            <th scope="col" class="px-6 py-3 text-center">Pilih</th>
                            <th scope="col" class="px-6 py-3 text-center rounded-tr-lg">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($invoices as $index => $invoice)
                        <tr class="bg-white hover:bg-gray-50 transition duration-150 ease-in-out">

                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $invoice->created_at->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">
                                {{ $invoice->no_invoice }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $invoice->nama_klien }}
                            </td>

                            {{-- No Surat Penawaran --}}
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap text-xs">
                                @if($invoice->offer)
                                    @php
                                        $bulanRomawi = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
                                        $tglOffer = $invoice->offer->created_at;
                                        $romawi = $bulanRomawi[$tglOffer->format('n')];
                                        $tahun = $tglOffer->format('Y');
                                    @endphp
                                    <span class="bg-gray-100 text-gray-600 py-1 px-2 rounded-full border border-gray-300">
                                        00{{ $invoice->offer->id }}/SP/TGI-1/{{ $romawi }}/{{ $tahun }}
                                    </span>
                                @else
                                    <span class="text-red-500 italic">-</span>
                                @endif
                            </td>

                            {{-- File PO Client --}}
                            <td class="px-6 py-4 text-center whitespace-nowrap text-xs">
                                @if($invoice->po_file_path)
                                    <a href="{{ $invoice->po_file_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 font-bold rounded-lg border border-blue-200 hover:bg-blue-100 transition shadow-sm">
                                        <span>📄 File PO</span>
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">Belum ada</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right whitespace-nowrap font-bold text-green-600">
                                Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}
                            </td>

                            {{-- Status Column --}}
                            <td class="px-6 py-4 text-center font-bold whitespace-nowrap">
                                @if($invoice->is_paid)
                                    <span class="bg-green-100 text-green-700 py-1 px-2 rounded-full text-xs border border-green-300">Paid</span>
                                @elseif($invoice->created_at->copy()->addMonth() < now())
                                    <span class="bg-red-100 text-red-700 py-1 px-2 rounded-full text-xs border border-red-300">Overdue</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 py-1 px-2 rounded-full text-xs border border-yellow-300">Due / Unpaid</span>
                                @endif
                            </td>

                            {{-- Pilih Checkbox Column --}}
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" name="invoice_ids[]" value="{{ $invoice->id }}" class="soa-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer" title="Pilih untuk SOA">
                            </td>

                            {{-- Action Dropdown (DIPERBAIKI) --}}
                            <td class="px-6 py-4 text-center">
                            {{-- Tambahkan 'relative' di td agar posisi absolute dropdown benar --}}
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open" @click.away="open = false" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-3 py-1.5 bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                                    Options
                                    <svg class="-mr-1 ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                {{-- Logika Posisi Dropdown --}}
                                <div x-show="open"
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50
                                            {{ $index >= count($invoices) - 2 ? 'bottom-full mb-2 origin-bottom-right' : 'mt-2 origin-top-right' }}">

                                    <div class="py-1" role="menu">
                                        <a href="{{ route('invoice.show', $invoice->id) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700" role="menuitem">
                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Detail
                                        </a>

                                        @if($invoice->po_file_path)
                                        <a href="{{ $invoice->po_file_url }}" target="_blank" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700" role="menuitem">
                                            <svg class="mr-3 h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            File PO Client
                                        </a>
                                        @endif

                                        {{-- Add Payment --}}
                                        <button type="button" onclick="openPaymentModal({{ $invoice->id }}, {{ $invoice->grand_total }}, {{ $invoice->paid_amount }}, '{{ $invoice->no_invoice }}', {{ json_encode($invoice->paymentTransactions->map(fn($t) => ['amount' => $t->amount, 'date' => $t->created_at->format('d M Y'), 'receipt' => $t->payment_receipt ? Storage::url($t->payment_receipt) : null])) }})" class="w-full text-left group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700" role="menuitem">
                                            <span class="mr-3 text-lg">💰</span>
                                            Add Payment
                                        </button>

                                        <a href="{{ route('invoice.edit', $invoice->id) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700" role="menuitem">
                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>

                                        <form action="{{ route('invoice.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus invoice ini? Data tidak bisa dikembalikan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="group flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-800" role="menuitem">
                                                <svg class="mr-3 h-5 w-5 text-red-400 group-hover:text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-lg font-medium">Belum ada data invoice.</p>
                                <p class="text-sm">Silakan buat invoice baru dari menu di atas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </form>

        <div class="mt-6">
            {{ $invoices->appends(['search' => $search ?? ''])->links() }}
        </div>

    </div>
</div>

{{-- Payment Modal --}}
<div id="paymentModal" class="fixed inset-0 z-[110] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closePaymentModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="paymentForm" method="POST" action="" enctype="multipart/form-data" class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                @csrf
                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                    Tambah Pembayaran - <span id="modalInvoiceNo" class="text-blue-600"></span>
                </h3>
                
                <div class="grid grid-cols-2 gap-4 mb-4 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Total Tagihan (Grand Total)</p>
                        <p id="modalTotalTagihan" class="text-sm font-bold text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Sudah Dibayar</p>
                        <p id="modalPaidAmountText" class="text-sm font-bold text-green-600">-</p>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500">Sisa Tagihan:</p>
                    <p id="modalSisaTagihan" class="text-xl font-bold text-red-600">Rp 0</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal Pembayaran Baru</label>
                    <div class="flex items-center gap-2">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="paid_amount" id="paidAmountInput" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required min="1" step="1">
                        </div>
                        <button type="button" onclick="setLunas()" class="bg-blue-100 text-blue-700 px-3 py-2 rounded-md text-sm font-bold border border-blue-300 hover:bg-blue-200">
                            Lunas
                        </button>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Transfer Baru (Opsional)</label>
                    <input type="file" name="payment_receipt" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                </div>

                <div id="transactionsContainer" class="mb-6 hidden">
                    <h4 class="text-sm font-bold text-gray-700 mb-2 border-b pb-1">Riwayat Pembayaran</h4>
                    <ul id="transactionsList" class="space-y-2 text-sm text-gray-600 max-h-40 overflow-y-auto">
                        <!-- Transaksi dimasukkan via JS -->
                    </ul>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closePaymentModal()" class="bg-gray-200 text-gray-800 px-4 py-2 rounded font-medium hover:bg-gray-300">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded font-bold hover:bg-blue-700">Simpan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.soa-checkbox');
        const btnSoa = document.getElementById('buatSoaBtn');

        function updateBtnStatus() {
            const checkedCount = document.querySelectorAll('.soa-checkbox:checked').length;
            btnSoa.disabled = checkedCount < 2;
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBtnStatus);
        });
    });

    let currentSisaTagihan = 0;

    function openPaymentModal(invoiceId, totalTagihan, paidAmount, invoiceNo, transactions) {
        currentSisaTagihan = totalTagihan - paidAmount;
        if (currentSisaTagihan < 0) currentSisaTagihan = 0;
        
        document.getElementById('modalInvoiceNo').innerText = invoiceNo;
        document.getElementById('modalTotalTagihan').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalTagihan);
        document.getElementById('modalPaidAmountText').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(paidAmount);
        document.getElementById('modalSisaTagihan').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(currentSisaTagihan);
        
        document.getElementById('paidAmountInput').value = '';
        
        const txContainer = document.getElementById('transactionsContainer');
        const txList = document.getElementById('transactionsList');
        txList.innerHTML = '';

        if (transactions && transactions.length > 0) {
            txContainer.classList.remove('hidden');
            transactions.forEach(tx => {
                let receiptLink = tx.receipt 
                    ? `<a href="${tx.receipt}" target="_blank" class="text-blue-500 hover:underline">Lihat Bukti</a>` 
                    : '<span class="text-gray-400 italic">Tanpa bukti</span>';
                
                txList.innerHTML += `
                    <li class="flex justify-between items-center bg-gray-50 p-2 rounded border border-gray-100">
                        <div>
                            <span class="font-bold">Rp ${new Intl.NumberFormat('id-ID').format(tx.amount)}</span>
                            <br><span class="text-xs text-gray-500">${tx.date}</span>
                        </div>
                        <div class="text-xs">
                            ${receiptLink}
                        </div>
                    </li>
                `;
            });
        } else {
            txContainer.classList.add('hidden');
        }

        // Set action form URL
        document.getElementById('paymentForm').action = `/${invoiceId}/payment`;
        
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    function setLunas() {
        document.getElementById('paidAmountInput').value = currentSisaTagihan;
    }
</script>
@endsection