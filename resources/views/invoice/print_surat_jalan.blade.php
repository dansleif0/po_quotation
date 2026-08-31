<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - {{ $invoice->no_invoice }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 12.7mm;
            }

            body {
                margin: 0;
                padding: 0;
                background-color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            #main-container {
                width: 100% !important;
                margin: 0 auto !important;
                padding: 12.7mm !important;
                box-shadow: none !important;
                border: none !important;
            }

            table {
                page-break-inside: auto;
                width: 100%;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }

        body {
            background-color: #f3f4f6;
        }

        #main-container {
            background-color: white;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 12.7mm;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .nav-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body class="bg-gray-100 text-black">

    <div class="nav-floating no-print">
        <button onclick="window.print()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center gap-2 transition cursor-pointer">
            <span>🖨️</span> Cetak Surat Jalan
        </button>
        <button onclick="window.close()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow-lg transition cursor-pointer">
            Tutup
        </button>
    </div>

    <div id="main-container" class="max-w-[21cm] mx-auto bg-white shadow-xl my-10 p-10 print:shadow-none print:my-0">

        {{-- HEADER KOP SURAT --}}
        <header class="w-full mb-6">
            <div class="w-full">
                <img src="{{ asset('images/kopsurat.jpg') }}" alt="Kop Surat" class="w-full h-auto">
            </div>
            <div class="w-full border-b-[4px] border-[#d32f2f] mt-1"></div>
        </header>

        {{-- JUDUL SURAT JALAN --}}
        <div class="text-center my-6">
            <h1 class="text-2xl font-extrabold tracking-wider uppercase underline">SURAT JALAN</h1>
            <p class="text-sm font-semibold text-gray-700 mt-1">No. SJ: SJ-{{ $invoice->no_invoice }}</p>
        </div>

        <section class="mt-6 flex justify-between text-sm">
            <div class="w-1/2">
                <p class="font-bold mb-1">KEPADA YTH:</p>
                <p class="font-bold text-base uppercase">{{ $invoice->nama_klien }}</p>
                @if($invoice->offer && $invoice->offer->client_details)
                <p class="text-gray-700 whitespace-pre-line">{{ $invoice->offer->client_details }}</p>
                @endif
            </div>
            <div class="w-1/2 text-right">
                <div class="flex justify-end mb-1">
                    <span class="w-28 text-left font-bold">Tanggal Kirim</span>
                    <span class="text-left">: {{ \Carbon\Carbon::parse($invoice->created_at)->format('d F Y') }}</span>
                </div>
                <div class="flex justify-end">
                    <span class="w-28 text-left font-bold">Ref Invoice</span>
                    <span class="text-left">: {{ $invoice->no_invoice }}</span>
                </div>
            </div>
        </section>

        <section class="mt-8 text-sm">
            <p class="mb-2">Mohon diterima barang-barang tersebut di bawah ini dengan kondisi baik dan cukup:</p>

            <table class="w-full mt-4 border-collapse border border-black">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-black p-2 text-center w-12 font-semibold">No.</th>
                        <th class="border border-black p-2 text-left font-semibold">Nama Barang / Deskripsi Pekerjaan</th>
                        <th class="border border-black p-2 text-center w-28 font-semibold">Packing</th>
                        <th class="border border-black p-2 text-center w-24 font-semibold">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rowCounter = 1; @endphp
                    @if($invoice->offer && $invoice->offer->items->isNotEmpty())
                        @foreach($invoice->offer->items as $item)
                            <tr>
                                <td class="border border-black p-2 text-center">{{ $rowCounter++ }}</td>
                                <td class="border border-black p-2 font-bold">{{ $item->nama_produk }}</td>
                                <td class="border border-black p-2 text-center">{{ $item->packing_size ?? '-' }}</td>
                                <td class="border border-black p-2 text-center font-bold">{{ $item->qty_order > 0 ? $item->qty_order : ($item->volume > 0 ? $item->volume : 1) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="border border-black p-2 text-center">1</td>
                            <td class="border border-black p-2 font-bold">{{ optional($invoice->offer)->perihal ?? 'Pengiriman Supply Produk Cat Jotun' }}</td>
                            <td class="border border-black p-2 text-center">-</td>
                            <td class="border border-black p-2 text-center font-bold">1 Paket</td>
                        </tr>
                    @endif

                    @foreach($invoice->additions as $addition)
                    <tr>
                        <td class="border border-black p-2 text-center">{{ $rowCounter++ }}</td>
                        <td class="border border-black p-2">{{ $addition->nama_pekerjaan }}</td>
                        <td class="border border-black p-2 text-center">-</td>
                        <td class="border border-black p-2 text-center font-bold">1 Pekerjaan</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        {{-- TANDA TANGAN PENERIMA & PENGIRIM --}}
        <section class="mt-16 grid grid-cols-3 text-center text-sm gap-4">
            <div>
                <p class="font-semibold">Tanda Terima,</p>
                <div class="h-24"></div>
                <p class="font-bold border-b border-black inline-block px-8">( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; )</p>
                <p class="text-xs text-gray-500 mt-1">Nama Clear & Stempel</p>
            </div>
            <div>
                <p class="font-semibold">Sopir / Kurir,</p>
                <div class="h-24"></div>
                <p class="font-bold border-b border-black inline-block px-8">( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; )</p>
                <p class="text-xs text-gray-500 mt-1">Pengirim</p>
            </div>
            <div>
                <p class="font-semibold">Hormat Kami,</p>
                <p class="font-bold">PT. Tasniem Gerai Inspirasi</p>
                <div class="h-24 relative flex items-center justify-center">
                    <img src="{{ asset('images/ttd.png') }}" alt="Tanda Tangan" class="h-20 opacity-90 mx-auto">
                </div>
                <p class="font-bold text-gray-800">SAMSU RIZAL</p>
                <p class="text-xs text-gray-600">General Manager</p>
            </div>
        </section>

    </div>

</body>

</html>
