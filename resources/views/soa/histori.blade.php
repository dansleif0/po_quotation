@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-7xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Histori Statement of Account (SOA)
            </h1>
        </div>

        <form action="{{ route('soa.histori') }}" method="GET" class="mb-6">
            <div class="flex gap-2">
                <input type="text"
                       name="search"
                       placeholder="Cari No. SOA atau Nama Klien..."
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
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 shadow-sm" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg border border-gray-200 relative">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs text-white uppercase bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 rounded-tl-lg">Tanggal SOA</th>
                        <th scope="col" class="px-6 py-3">No. SOA</th>
                        <th scope="col" class="px-6 py-3">Nama Klien</th>
                        <th scope="col" class="px-6 py-3 text-center">Jml Invoice</th>
                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                        <th scope="col" class="px-6 py-3 text-center rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($soas as $index => $soa)
                    <tr class="bg-white hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($soa->tanggal_soa)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">
                            {{ $soa->no_soa }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $soa->nama_klien }}
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-gray-700 whitespace-nowrap">
                            {{ $soa->invoices->count() }}
                        </td>
                        @php 
                            $totalTagihan = $soa->invoices->sum('grand_total'); 
                        @endphp
                        <td class="px-6 py-4 text-center font-bold whitespace-nowrap">
                            @if($soa->is_paid)
                                <span class="bg-green-100 text-green-700 py-1 px-2 rounded-full text-xs border border-green-300">Paid</span>
                            @elseif(\Carbon\Carbon::parse($soa->tanggal_soa)->addMonth() < now())
                                <span class="bg-red-100 text-red-700 py-1 px-2 rounded-full text-xs border border-red-300">Overdue</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 py-1 px-2 rounded-full text-xs border border-yellow-300">Due / Unpaid</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center relative">
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open" @click.away="open = false" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-3 py-1.5 bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                                    Aksi
                                    <svg class="-mr-1 ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-[100]" style="display: none;">
                                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                        {{-- Cetak --}}
                                        <a href="{{ route('soa.print', $soa->id) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                            🖨️ Cetak SOA
                                        </a>

                                        {{-- Add Payment --}}
                                        <button type="button" onclick='openPaymentModal({{ $soa->id }}, {{ $totalTagihan ?? 0 }}, {{ $soa->paid_amount ?? 0 }}, "{{ $soa->no_soa }}", {{ json_encode($soa->paymentTransactions->map(fn($t) => ["amount" => $t->amount, "date" => $t->created_at->format("d M Y"), "receipt" => $t->payment_receipt ? asset("storage/" . $t->payment_receipt) : null])) }})' class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                            💰 Add Payment
                                        </button>

                                        {{-- Hapus --}}
                                        <form action="{{ route('soa.destroy', $soa->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus SOA ini? (Invoice tidak akan ikut terhapus)');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-700 hover:bg-red-50 hover:text-red-900 font-bold" role="menuitem">
                                                🗑️ Hapus SOA
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <p class="text-lg font-medium">Belum ada data SOA.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $soas->appends(['search' => $search ?? ''])->links() }}
        </div>
    </div>
</div>

{{-- Modal Add Payment --}}
<div id="paymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[200] flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Add Payment - <span id="modalSoaNo"></span></h3>
            <button type="button" onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <form id="paymentForm" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Total Tagihan SOA:</p>
                    <p id="modalTotalTagihan" class="text-xl font-bold text-gray-900">Rp 0</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500">Telah Dibayar Sebelumnya:</p>
                    <p id="modalPaidAmountText" class="text-md font-bold text-green-600">Rp 0</p>
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
    let currentSisaTagihan = 0;

    function openPaymentModal(soaId, totalTagihan, paidAmount, soaNo, transactions) {
        currentSisaTagihan = totalTagihan - paidAmount;
        if (currentSisaTagihan < 0) currentSisaTagihan = 0;
        
        document.getElementById('modalSoaNo').innerText = soaNo;
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
        document.getElementById('paymentForm').action = `/soa/${soaId}/payment`;
        
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
