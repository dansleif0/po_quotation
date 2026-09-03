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
                size: 24.1cm 13.95cm;
                margin: 0 !important;
            }

            body {
                margin: 0 !important;
                padding: 0 !important;
                background-color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            #main-container {
                width: 24.1cm !important;
                height: 13.95cm !important;
                margin: 0 !important;
                padding: 4mm 8mm !important;
                box-sizing: border-box !important;
                box-shadow: none !important;
                border: none !important;
                display: flex !important;
                flex-direction: column !important;
                overflow: hidden !important;
            }
        }

        body {
            background-color: #f3f4f6;
        }

        #main-container {
            background-color: white;
            width: 24.1cm;
            min-height: 13.95cm;
            margin: 20px auto;
            padding: 4mm 8mm;
            box-sizing: border-box;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
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

    <div id="main-container" class="bg-white text-black font-sans text-[11px]">

        {{-- HEADER SECTION --}}
        <div class="flex justify-between items-start pb-1 gap-2">
            {{-- Left Header: Company Name, Address & Nomor/Tanggal --}}
            <div class="w-5/12 leading-snug text-black">
                <h2 class="font-extrabold text-sm text-black tracking-tight">PT. TASNIEM GERAI INSPIRASI</h2>
                <p>Komp.Ruko KDA Junction Blok C 8-9</p>
                <p>Batam Centre</p>
                <div class="mt-2 space-y-0.5">
                    <div class="flex">
                        <span class="w-20 font-bold uppercase">NOMOR</span>
                        <span>: {{ $invoice->no_invoice }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-20 font-bold uppercase">TANGGAL</span>
                        <span>: {{ \Carbon\Carbon::parse($invoice->created_at)->format('d F Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Center Header: Title SURAT JALAN --}}
            <div class="w-3/12 text-center pt-2">
                <h1 class="text-xl font-extrabold tracking-wider uppercase underline">SURAT JALAN</h1>
            </div>

            {{-- Right Header: Kepada, Telp, Fax, Sales --}}
            <div class="w-4/12 flex flex-col items-end">
                <div class="w-full max-w-[280px] space-y-0.5 text-[11px]">
                    <div class="flex items-start">
                        <span class="w-16 font-bold shrink-0">Kepada</span>
                        <div class="font-bold text-black uppercase leading-tight">
                            : {{ strtoupper($invoice->nama_klien) }}
                            @if($invoice->offer && $invoice->offer->client_details)
                            <div class="font-normal text-[11px] text-gray-800 uppercase leading-tight mt-0.5">{{ $invoice->offer->client_details }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="w-16 font-bold shrink-0">Telp.</span>
                        <span>: -</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-16 font-bold shrink-0">Fax</span>
                        <span>:</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-16 font-bold shrink-0">Sales</span>
                        <span class="font-bold">: </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN TABLE (Tighter py-0.5) --}}
        <div class="my-1.5">
            <table class="w-full text-[11px] border-collapse">
                <thead>
                    <tr class="border-t border-b border-black font-bold text-black uppercase">
                        <th class="py-0.5 px-1 text-center w-8">NO</th>
                        <th class="py-0.5 px-2 text-left">NAMA BARANG</th>
                        <th class="py-0.5 px-2 text-center w-24">JUMLAH</th>
                        <th class="py-0.5 px-2 text-center w-16">BONUS</th>
                        <th class="py-0.5 px-2 text-center w-32">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $rowCounter = 1;
                        $parseNum = function($str) {
                            if (!$str) return 0;
                            $str = str_replace(',', '.', (string)$str);
                            preg_match('/([0-9]+(\.[0-9]+)?)/', $str, $m);
                            return isset($m[1]) ? (float)$m[1] : 0;
                        };
                    @endphp
                    @if(!empty($invoice->tampilkan_comp_b) && $invoice->offer && $invoice->offer->items->isNotEmpty())
                        @foreach($invoice->offer->items as $item)
                            @php
                                $qty = $item->qty_order > 0 ? $item->qty_order : ($item->volume > 0 ? $item->volume : 1);
                                $packing = $item->packing_size ?? '';
                                $prod = $item->product ?? \App\Models\Product::where('nama_produk', $item->nama_produk)->first();
                                $compB = $item->comp_b ?? $prod?->comp_b;
                                $packingB = $prod?->packing_size_b ?? $item->packing_size_b ?? '';

                                $numTotal = $parseNum($packing);
                                $numB = $parseNum($packingB);
                                $numA = max(0, $numTotal - $numB);
                                $unit = 'L';
                                if ($packing && preg_match('/[a-zA-Z]+/', $packing, $uMatch)) {
                                    $unit = $uMatch[0];
                                }
                                $packingA = ($numA > 0) ? (rtrim(rtrim(number_format($numA, 2, '.', ''), '0'), '.') . ' ' . $unit) : $packing;

                                $namaCompA = $item->nama_produk;
                                if ($compB && !str_contains(strtoupper($namaCompA), 'CPA')) {
                                    $namaCompA .= ' CPA';
                                }
                                $namaCompB = $compB;
                                if ($compB && !str_contains(strtoupper($namaCompB), 'CPB')) {
                                    $namaCompB .= ' CPB';
                                }
                            @endphp

                            @if(!empty($compB))
                                {{-- Row 1: Comp A --}}
                                <tr class="align-top">
                                    <td class="py-0.5 px-1 text-center font-semibold">{{ $rowCounter++ }}.</td>
                                    <td class="py-0.5 px-2">
                                        <div class="flex justify-between items-center font-bold">
                                            <span>{{ strtoupper($namaCompA) }}</span>
                                            <span class="font-normal text-[10px] pr-4">{{ $packingA }}</span>
                                        </div>
                                    </td>
                                    <td class="py-0.5 px-2 text-center font-semibold">{{ number_format($qty, 0) }} CAN</td>
                                    <td class="py-0.5 px-2 text-center"></td>
                                    <td class="py-0.5 px-2 text-center">
                                        @if(!empty($item->keterangan))
                                            <span class="text-[9px] font-normal text-gray-700">{{ $item->keterangan }}</span>
                                        @endif
                                    </td>
                                </tr>
                                {{-- Row 2: Comp B --}}
                                <tr class="align-top">
                                    <td class="py-0.5 px-1 text-center font-semibold">{{ $rowCounter++ }}.</td>
                                    <td class="py-0.5 px-2">
                                        <div class="flex justify-between items-center font-bold">
                                            <span>{{ strtoupper($namaCompB) }}</span>
                                            <span class="font-normal text-[10px] pr-4">{{ $packingB }}</span>
                                        </div>
                                    </td>
                                    <td class="py-0.5 px-2 text-center font-semibold">{{ number_format($qty, 0) }} CAN</td>
                                    <td class="py-0.5 px-2 text-center"></td>
                                    <td class="py-0.5 px-2 text-center"></td>
                                </tr>
                            @else
                                <tr class="align-top">
                                    <td class="py-0.5 px-1 text-center font-semibold">{{ $rowCounter++ }}.</td>
                                    <td class="py-0.5 px-2">
                                        <div class="flex justify-between items-center font-bold">
                                            <span>{{ strtoupper($item->nama_produk) }}</span>
                                            <span class="font-normal text-[10px] pr-4">{{ $packing }}</span>
                                        </div>
                                    </td>
                                    <td class="py-0.5 px-2 text-center font-semibold">{{ number_format($qty, 0) }} CAN</td>
                                    <td class="py-0.5 px-2 text-center"></td>
                                    <td class="py-0.5 px-2 text-center">
                                        @if(!empty($item->keterangan))
                                            <span class="text-[9px] font-normal text-gray-700">{{ $item->keterangan }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @elseif($invoice->offer && $invoice->offer->items->isNotEmpty())
                        @foreach($invoice->offer->items as $item)
                            @php
                                $qty = $item->qty_order > 0 ? $item->qty_order : ($item->volume > 0 ? $item->volume : 1);
                                $packing = $item->packing_size ?? '';
                            @endphp
                            <tr class="align-top">
                                <td class="py-0.5 px-1 text-center font-semibold">{{ $rowCounter++ }}.</td>
                                <td class="py-0.5 px-2">
                                    <div class="flex justify-between items-center font-bold">
                                        <span>{{ strtoupper($item->nama_produk) }}</span>
                                        <span class="font-normal text-[10px] pr-4">{{ $packing }}</span>
                                    </div>
                                </td>
                                <td class="py-0.5 px-2 text-center font-semibold">{{ number_format($qty, 0) }} CAN</td>
                                <td class="py-0.5 px-2 text-center"></td>
                                <td class="py-0.5 px-2 text-center">
                                    @if(!empty($item->keterangan))
                                        <span class="text-[9px] font-normal text-gray-700">{{ $item->keterangan }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="align-top">
                            <td class="py-0.5 px-1 text-center font-semibold">1.</td>
                            <td class="py-0.5 px-2 font-bold">{{ strtoupper(optional($invoice->offer)->perihal ?? 'Pengiriman Supply Cat Jotun') }}</td>
                            <td class="py-0.5 px-2 text-center font-semibold">1 PAKET</td>
                            <td class="py-0.5 px-2 text-center"></td>
                            <td class="py-0.5 px-2 text-center"></td>
                        </tr>
                    @endif

                    @foreach($invoice->additions as $addition)
                    <tr class="align-top">
                        <td class="py-0.5 px-1 text-center font-semibold">{{ $rowCounter++ }}.</td>
                        <td class="py-0.5 px-2 font-bold">{{ strtoupper($addition->nama_pekerjaan) }}</td>
                        <td class="py-0.5 px-2 text-center font-semibold">1 Ls</td>
                        <td class="py-0.5 px-2 text-center"></td>
                        <td class="py-0.5 px-2 text-center"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="border-b border-black w-full mt-0.5"></div>
        </div>

        {{-- NOTES & PRINTED BY SECTION --}}
        <div class="my-1.5 text-[10px] font-sans space-y-0.5">
            <div>
                <span class="font-bold">Catatan :</span> {{ strtoupper($invoice->nama_klien) }} {{ $invoice->offer && $invoice->offer->client_details ? '- ' . strtoupper($invoice->offer->client_details) : '' }}
                @php
                    $no_po = '';
                    if ($invoice->offer) {
                        if ($invoice->offer->project_no) {
                            $no_po = $invoice->offer->project_no;
                        }
                        $po = \App\Models\PurchaseOrder::where('offer_id', $invoice->offer->id)->first();
                        if ($po && $po->po_number) {
                            $no_po = $po->po_number;
                        }
                    }
                @endphp
                @if($no_po)
                <br><span class="inline-block w-[58px]"></span>NO PO : {{ $no_po }}
                @endif
                @if($invoice->catatan_tambahan)
                <br><span class="inline-block w-[58px]"></span>{{ $invoice->catatan_tambahan }}
                @endif
            </div>
            <div class="text-[10px] text-black pt-0.5">
                <span class="font-bold">Printed By :</span> Admin, {{ date('H:i:s, l, d F Y') }}
            </div>
        </div>

        {{-- SIGNATURE SECTION (4 Columns: Bag. Administrasi, Kepala Gudang, Supir/Helper, Yang Menerima) --}}
        <div class="mt-auto grid grid-cols-4 text-center text-[10px] font-sans gap-6">
            <div class="flex flex-col justify-between h-12">
                <p class="font-normal text-black">Bag. Administrasi,</p>
                <div class="border-b border-black w-full"></div>
            </div>
            <div class="flex flex-col justify-between h-12">
                <p class="font-normal text-black">Kepala Gudang,</p>
                <div class="border-b border-black w-full"></div>
            </div>
            <div class="flex flex-col justify-between h-12">
                <p class="font-normal text-black">Supir/Helper,</p>
                <div class="border-b border-black w-full"></div>
            </div>
            <div class="flex flex-col justify-between h-12">
                <p class="font-normal text-black">Yang Menerima,</p>
                <div class="border-b border-black w-full"></div>
            </div>
        </div>

    </div>

</body>

</html>
