<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIUTANG STATEMENT - {{ $soa->no_soa }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                size: A4 portrait;
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
                padding: 0 !important; /* Biarkan @page margin yang bekerja saat di-print */
                box-shadow: none !important;
                border: none !important;
            }
        }
        body { background-color: #f3f4f6; }
        #main-container {
            background-color: white;
            width: 210mm; /* A4 Portrait width */
            min-height: 297mm; /* A4 Portrait height */
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
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center gap-2 transition cursor-pointer">
            <span>🖨️</span> Cetak SOA
        </button>
        <button onclick="window.close()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow-lg transition cursor-pointer">
            Tutup
        </button>
    </div>

    <div id="main-container" class="bg-white text-black font-sans text-[12px]">

        {{-- HEADER SECTION --}}
        <div class="w-full mb-6">
            <img src="{{ asset('images/kopsurat.jpg') }}" alt="Kop Surat Tasniem" class="w-full h-auto object-contain">
        </div>

        {{-- META INFO SECTION --}}
        <div class="flex justify-between items-start mt-6 mb-8">
            <div class="w-1/2 pt-4">
                <h1 class="text-xl font-bold uppercase underline mb-2">PIUTANG STATEMENT</h1>
                <div class="flex font-bold">
                    <span class="w-12 uppercase">ON :</span>
                    <span class="uppercase">{{ \Carbon\Carbon::parse($soa->tanggal_soa)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="mt-4 text-[10px] text-gray-700 font-medium">
                    <p>CR, RTC, DN, CN, DP</p>
                </div>
            </div>

            <div class="w-1/2 pl-12">
                <div class="border-2 border-black p-4 h-full font-bold uppercase text-[11px] leading-relaxed">
                    <p class="mb-2">To :</p>
                    <p class="text-[13px] mb-2">{{ $soa->nama_klien }}</p>
                    @php
                        // Coba ambil alamat/klien details dari invoice pertama
                        $firstInvoice = $soa->invoices->first();
                        $clientDetails = '';
                        if ($firstInvoice && $firstInvoice->offer && $firstInvoice->offer->client_details) {
                            $clientDetails = $firstInvoice->offer->client_details;
                        }
                    @endphp
                    @if($clientDetails)
                        <p class="mb-2">{{ $clientDetails }}</p>
                    @endif
                    <p>Phone : +62778468057</p> {{-- Hardcoded in example, adjust as needed or use client phone --}}
                </div>
            </div>
        </div>

        {{-- MAIN TABLE --}}
        <div class="my-4">
            <table class="w-full border-collapse border border-black text-center text-[12px]">
                <thead>
                    <tr class="font-bold">
                        <th class="border border-black py-1 px-2 w-28">DATE</th>
                        <th class="border border-black py-1 px-2 w-28">DUE DATE</th>
                        <th class="border border-black py-1 px-2 w-24">INVOICE</th>
                        <th class="border border-black py-1 px-2">KETERANGAN</th>
                        <th class="border border-black py-1 px-2 w-32">DEBIT</th>
                        <th class="border border-black py-1 px-2 w-32">CREDIT</th>
                    </tr>
                </thead>
                <tbody>
                    @php $runningTotal = 0; @endphp
                    @foreach($soa->invoices as $invoice)
                        @php 
                            $runningTotal += $invoice->grand_total; 
                            $dueDate = \Carbon\Carbon::parse($invoice->created_at)->addMonth();
                        @endphp
                        <tr>
                            <td class="border border-black py-1 px-2">{{ $invoice->created_at->format('d/m/Y') }}</td>
                            <td class="border border-black py-1 px-2">{{ $dueDate->format('d/m/Y') }}</td>
                            <td class="border border-black py-1 px-2">{{ explode('/', $invoice->no_invoice)[0] }}</td>
                            <td class="border border-black py-1 px-2">{{ $invoice->pivot->keterangan ?? '' }}</td>
                            <td class="border border-black py-1 px-2 text-right">{{ number_format($invoice->grand_total, 0, ',', ',') }}</td>
                            <td class="border border-black py-1 px-2 text-right">{{ number_format($runningTotal, 0, ',', ',') }}</td>
                        </tr>
                    @endforeach
                    {{-- Empty row at the bottom --}}
                    <tr>
                        <td class="border border-black py-3 px-2"></td>
                        <td class="border border-black py-3 px-2"></td>
                        <td class="border border-black py-3 px-2"></td>
                        <td class="border border-black py-3 px-2"></td>
                        <td class="border border-black py-3 px-2"></td>
                        <td class="border border-black py-3 px-2"></td>
                    </tr>
                </tbody>
            </table>
            
            {{-- Total outside table --}}
            <div class="w-full flex justify-end mt-1">
                <div class="w-32 text-right px-2 font-bold text-[12px]">
                    {{ number_format($runningTotal, 0, ',', ',') }}
                </div>
            </div>
        </div>

        {{-- BANK DETAILS --}}
        <div class="mt-4 font-bold text-[12px] leading-relaxed">
            <p>BANKERS PT. TASNIEM GERAI INSPIRASI</p>
            <p>BANK BRI (IDR) : 033101001817306</p>
            <p>BANK MANDIRI (IDR) : 1090080002223</p>
        </div>

        {{-- SIGNATURES --}}
        <div class="mt-8 flex justify-between items-end pb-8 text-[12px]">
            <div class="w-1/3">
                <p class="mb-20">Received By,</p>
                <div class="border-b border-black w-56"></div>
            </div>
            
            <div class="w-1/3 flex flex-col items-center">
                <p class="mb-2 w-full text-center">Yours Faithfully,</p>
                <div class="h-24 w-48 flex items-center justify-center relative mb-1">
                    <img src="{{ asset('images/ttd.png') }}" alt="Stempel & TTD" class="max-h-full max-w-full object-contain mix-blend-multiply">
                </div>
                <div class="w-56 text-center">
                    <p class="mb-1">Samsu Rizal</p>
                    <div class="border-t border-black w-full pt-1 text-[11px] text-gray-700">
                        <p>PT. TASNIEM GERAI INSPIRASI</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
