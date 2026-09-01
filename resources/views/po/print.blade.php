<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Purchase Order - {{ $po->po_number }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 12.7mm; /* Narrow Margin (0.5 in) */
            }

            body {
                background-color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                font-family: Arial, Helvetica, sans-serif !important;
            }

            .no-print {
                display: none !important;
            }

            #print-paper {
                width: 100% !important;
                min-height: auto !important;
                height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
                overflow: hidden !important;
            }
        }

        body {
            background-color: #f4f4f4;
            font-family: Arial, Helvetica, sans-serif;
            color: #000000;
        }

        #print-paper {
            background-color: white;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 12.7mm; /* Narrow Margin (0.5 in) */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .nav-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
            display: flex;
            gap: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #000000;
            padding: 5px 8px;
        }
    </style>
</head>

<body class="text-black text-xs">

    {{-- Navigasi Terapung (Hanya muncul di layar) --}}
    <div class="nav-floating no-print">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-full shadow-xl flex items-center gap-2 transition cursor-pointer">
            <span>🖨️</span> Cetak Purchase Order
        </button>
        <button onclick="window.close()" class="bg-slate-600 hover:bg-slate-700 text-white font-bold py-2.5 px-5 rounded-full shadow-lg transition cursor-pointer">
            Tutup
        </button>
    </div>

    <div id="print-paper" class="print:m-0 print:p-0 print:shadow-none">

        @php
            $tglPo = \Carbon\Carbon::parse($po->tanggal_po);
            $tglStr = $tglPo->format('d - m - Y');
            $tglFullStr = $tglPo->translatedFormat('d F Y');
        @endphp

        {{-- Official Kop Surat Header & PO Title --}}
        <div class="flex justify-between items-start mb-4 pb-2 border-b border-gray-200">
            <div class="flex-1 pr-4">
                <img src="{{ asset('images/kopsurat.jpg') }}" alt="Kop Surat PT Tasniem Gerai Inspirasi" class="max-w-[450px] w-full h-auto object-contain">
            </div>
            <div class="text-right pt-1 whitespace-nowrap">
                <h1 class="text-xl font-bold uppercase text-black font-sans tracking-wide"><u>P U R C H A S E &nbsp; O R D E R</u></h1>
                <p class="font-bold text-base mt-1 text-black font-sans tracking-tight">{{ $po->po_number }}</p>
            </div>
        </div>

        {{-- Grid Supplier, Deliver To & Document Info --}}
        <div class="grid grid-cols-12 gap-3 mb-3 text-[11px]">
            {{-- Box 1: SUPPLIER --}}
            <div class="col-span-4 border border-black rounded-[1rem] p-3 min-h-[100px]">
                <p class="text-center tracking-[0.2em] mb-2"><span class="border-b border-gray-400 pb-0.5 px-4">S U P P L I E R</span></p>
                <p class="uppercase text-[11px] mb-0.5">{{ $po->supplier_name ?: 'PT CIPTA MARITIM PERKASA' }}</p>
                <p class="text-[11px] leading-tight">{{ $po->supplier_address ?: 'Ruko Tunas Regency Blok A5 No 09 – 10 Tanjung Uncang' }}</p>
            </div>

            {{-- Box 2: DELIVER TO --}}
            <div class="col-span-4 border border-black rounded-[1rem] p-3 min-h-[100px]">
                <p class="text-center tracking-[0.2em] mb-2"><span class="border-b border-gray-400 pb-0.5 px-4">D E L I V E R &nbsp; T O</span></p>
                <p class="uppercase text-[11px] mb-0.5">{{ $po->deliver_to_name ?: 'PT TASNIEM GERAI INSPIRASI' }}</p>
                <p class="text-[11px] leading-tight">{{ $po->deliver_to_address ?: 'Komp. Ruko KDA Junction Blok C 8-9' }}</p>
            </div>

            {{-- Box 3: Metadata Grid --}}
            <div class="col-span-4 pl-4 space-y-1.5 text-[11px] font-sans">
                <div class="flex items-start">
                    <span class="w-28 shrink-0">DATE</span>
                    <span>: {{ $tglStr }}</span>
                </div>
                <div class="flex items-start">
                    <span class="w-28 shrink-0">CURRENCY</span>
                    <span>: {{ $po->currency ?: 'IDR' }}</span>
                </div>
                <div class="flex items-start">
                    <span class="w-28 shrink-0">DELIVERY DATE</span>
                    <span>: {{ $po->delivery_date ?: '-' }}</span>
                </div>
                <div class="flex items-start">
                    <span class="w-28 shrink-0">OFFER LETTER</span>
                    <span class="break-all">: {{ $po->offer_letter ?: ($po->offer->no_surat ?? '-') }}</span>
                </div>
                <div class="flex items-start">
                    <span class="w-28 shrink-0">PAYMENT</span>
                    <span>: {{ $po->payment_term ?: 'BANK TRANSFER' }}</span>
                </div>
            </div>
        </div>

        {{-- Job Project Line --}}
        <div class="mb-3 text-sm">
            @php
                $jobProjectVal = $po->job_project;
                if (empty($jobProjectVal) || $jobProjectVal === 'WCS-26-0927_J25-5438-01') {
                    if ($po->offer) {
                        $jobProjectVal = !empty($po->offer->project_no) ? $po->offer->project_no : $po->offer->no_surat;
                    } elseif (!empty($po->offer_letter)) {
                        $jobProjectVal = $po->offer_letter;
                    }
                }
            @endphp
            <p class="font-bold">JOB <span class="underline">PROJECT</span> : {{ $jobProjectVal ?: '-' }}</p>
        </div>

        {{-- Items Table --}}
        <div class="mb-3">
            <table class="w-full text-[11px] text-black border border-black">
                <thead>
                    <tr class="font-bold text-center bg-white">
                        <th class="w-8 border border-black py-1.5 px-1">NO</th>
                        <th class="border border-black py-1.5 px-2 text-center">Product</th>
                        <th class="w-20 border border-black py-1.5 px-1 text-center">Packing<br>Size (L)</th>
                        <th class="w-16 border border-black py-1.5 px-1 text-center">Qty<br>Order</th>
                        <th class="w-24 border border-black py-1.5 px-1 text-center">Consumption<br>(L)</th>
                        <th class="w-24 border border-black py-1.5 px-2 text-center">Price per (L)</th>
                        <th class="w-28 border border-black py-1.5 px-2 text-center">Total price</th>
                    </tr>
                </thead>
                <tbody>
                    @php $calcTotal = 0; @endphp
                    @forelse($po->items as $idx => $item)
                    @php
                        $calcTotal += $item->total_price;
                    @endphp
                    <tr>
                        <td class="text-center border border-black py-1 px-1 align-middle">{{ $idx + 1 }}</td>
                        <td class="uppercase border border-black py-1 px-2 align-middle">{{ $item->nama_produk }}</td>
                        <td class="text-center border border-black py-1 px-1 align-middle">{{ $item->packing_size }}</td>
                        <td class="text-center border border-black py-1 px-1 align-middle">{{ $item->qty_order + 0 }}</td>
                        <td class="text-center border border-black py-1 px-1 align-middle">{{ $item->consumption_l + 0 }}</td>
                        <td class="text-right border border-black py-1 px-2 align-middle">{{ number_format($item->price_per_liter, 0, '.', ',') }}</td>
                        <td class="text-right border border-black py-1 px-2 align-middle">{{ number_format($item->total_price, 0, '.', ',') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-3 italic text-gray-500">Tidak ada rincian produk.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="border-0 pt-1.5 pb-0 px-2 text-left text-[11px] font-bold">
                            <span class="underline">Note :</span> Quantity will be adjusted to Jotun standard packing size
                        </td>
                        <td class="border border-black py-1.5 px-2 text-right font-bold text-[12px]">
                            {{ number_format($po->total_nilai > 0 ? $po->total_nilai : $calcTotal, 0, '.', ',') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Notes & Signatures Section --}}
        <div class="grid grid-cols-12 gap-3 mt-3">
            {{-- Left Side: NOTES --}}
            <div class="col-span-7 text-[9px] space-y-0.5 min-w-0">
                <p class="font-bold text-[10px] tracking-wider uppercase mb-0.5">NOTES</p>
                <ol class="list-decimal list-inside space-y-0.5 text-slate-900 leading-tight">
                    <li>ITEMS TO BE DELIVERED ACCORDING TO THE SPECIFICATION AGREED AND APPROVED</li>
                    <li>PENALTY OF 5% PER WEEK WILL BE CHARGED UPON LATENESS FROM AGREED DELIVERY</li>
                    <li>PO NUMBER MUST BE STATED ON INVOICE AND DELIVERY ORDER</li>
                    <li>ACKNOWLEDGE PURCHASE ORDER ONCE RECEIVED</li>
                    <li>ALL SUPPLIER TO ADHERE TO THE INTEGRITY LETTER ACKNOWLEDGED</li>
                    <li>SOFTCOPY INVOICE AND DELIVERY ORDER TO BE SUBMITTED WITHIN 24 HOURS AFTER COMPLETE DELIVERY</li>
                    <li>OUTSTANDING INVOICE AND DELIVERY ORDER (COMPLETE DOCUMENTATION) TO BE SUBMITTED NOT LATER THAN 30 DAYS OR ELSE IT WILL BE FORFEITED</li>
                </ol>
            </div>

            {{-- Right Side: Signatures (3 Boxes) --}}
            <div class="col-span-5 grid grid-cols-3 border border-black text-center text-[10px] min-w-0">
                {{-- Box 1: Issued --}}
                <div class="border-r border-black p-1 flex flex-col justify-between min-h-[100px] min-w-0">
                    <p class="font-bold truncate">Issued,</p>
                    <div class="my-0.5 flex justify-center">
                        @if(file_exists(public_path('images/ttd_ardian.png')))
                            <img src="{{ asset('images/ttd_ardian.png') }}" alt="Signature Ardian" class="h-20 max-w-full object-contain mix-blend-multiply">
                        @else
                            <div class="h-10"></div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-[10px] uppercase border-t border-gray-300 pt-1 leading-none break-words">{{ $po->issued_by ?: 'Ardian Wijaya Kusuma' }}</p>
                        <p class="text-[8px] text-gray-800 uppercase mt-0.5 truncate">{{ strtoupper($tglPo->translatedFormat('d F Y')) }}</p>
                    </div>
                </div>

                {{-- Box 2: Approved --}}
                <div class="border-r border-black p-1 flex flex-col justify-between min-h-[100px] min-w-0">
                    <p class="font-bold truncate">Approved,</p>
                    <div class="my-0.5 flex justify-center relative">
                        @if(file_exists(public_path('images/ttd.png')))
                            <img src="{{ asset('images/ttd.png') }}" alt="Signature" class="h-16 max-w-full object-contain">
                        @else
                            <div class="h-10"></div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-[10px] uppercase border-t border-gray-300 pt-1 leading-none break-words">{{ $po->approved_by ?: 'Samsu Rizal' }}</p>
                        <p class="text-[8px] text-gray-800 uppercase mt-0.5 truncate">{{ strtoupper($tglPo->translatedFormat('d F Y')) }}</p>
                    </div>
                </div>

                {{-- Box 3: Verified --}}
                <div class="p-1 flex flex-col justify-between min-h-[100px] min-w-0">
                    <p class="font-bold truncate">Verified,</p>
                    <div class="my-0.5 h-10"></div>
                    <div class="min-w-0">
                        <p class="font-bold text-[10px] uppercase border-t border-gray-300 pt-1 leading-none">&nbsp;</p>
                        <p class="text-[8px] text-gray-800 uppercase mt-0.5">&nbsp;</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>
