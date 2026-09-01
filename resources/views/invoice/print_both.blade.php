<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice & Surat Jalan - {{ $invoice->no_invoice }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            @page {
                size: 24.1cm 27.9cm;
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
                height: 27.9cm !important;
                margin: 0 !important;
                padding: 6mm 8mm !important;
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
            min-height: 27.9cm;
            margin: 15px auto;
            padding: 6mm 8mm;
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

@php
if (!function_exists('terbilang_raw')) {
    function terbilang_raw($angka) {
        $angka = abs((float)$angka);
        $baca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        if ($angka < 12) {
            return " " . $baca[(int)$angka];
        } else if ($angka < 20) {
            return terbilang_raw($angka - 10) . " belas";
        } else if ($angka < 100) {
            return terbilang_raw(floor($angka / 10)) . " puluh" . terbilang_raw(fmod($angka, 10));
        } else if ($angka < 200) {
            return " seratus" . terbilang_raw($angka - 100);
        } else if ($angka < 1000) {
            return terbilang_raw(floor($angka / 100)) . " ratus" . terbilang_raw(fmod($angka, 100));
        } else if ($angka < 2000) {
            return " seribu" . terbilang_raw($angka - 1000);
        } else if ($angka < 1000000) {
            return terbilang_raw(floor($angka / 1000)) . " ribu" . terbilang_raw(fmod($angka, 1000));
        } else if ($angka < 1000000000) {
            return terbilang_raw(floor($angka / 1000000)) . " juta" . terbilang_raw(fmod($angka, 1000000));
        } else if ($angka < 1000000000000) {
            return terbilang_raw(floor($angka / 1000000000)) . " milyar" . terbilang_raw(fmod($angka, 1000000000));
        }
        return "";
    }
}

if (!function_exists('terbilang_rupiah_clean')) {
    function terbilang_rupiah_clean($angka) {
        $raw = terbilang_raw($angka);
        $clean = preg_replace('/\s+/', ' ', trim($raw));
        if (empty($clean)) return 'Nol rupiah';
        return ucfirst($clean) . ' rupiah';
    }
}
@endphp

    <div class="nav-floating no-print">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center gap-2 transition cursor-pointer">
            <span>🖨️</span> Cetak Keduanya (PDF)
        </button>
        <button onclick="window.close()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow-lg transition cursor-pointer">
            Tutup
        </button>
    </div>

    <div id="main-container" class="bg-white text-black font-sans text-[10px]">

        {{-- ========================================================================= --}}
        {{-- SECTION 1: INVOICE (TOP HALF) --}}
        {{-- ========================================================================= --}}
        <div class="invoice-section pb-2 flex-1 flex flex-col">
            {{-- HEADER SECTION --}}
            <div class="flex justify-between items-start pb-1 gap-2">
                <div class="flex items-start gap-2.5 w-6/12">
                    <img src="{{ asset('images/logo-tasniem.png') }}" alt="Logo Tasniem" class="h-12 w-auto object-contain shrink-0">
                    <div class="text-[10px] leading-tight text-black">
                        <h2 class="font-extrabold text-xs text-black tracking-tight">PT. TASNIEM GERAI INSPIRASI</h2>
                        <p>Komp. Ruko KDA Junction Blok C no 8-9 Batam Center</p>
                        <p>Phone / Whatsapp : +62 853-6114-9597</p>
                        <p>Website : https://tasniemgroup.com</p>
                    </div>
                </div>

                <div class="w-2/12 text-center pt-1">
                    <h1 class="text-base font-extrabold tracking-wider uppercase underline">INVOICE</h1>
                </div>

                <div class="w-4/12 flex justify-end items-start">
                    <img src="{{ asset('images/logo-jotun.png') }}" alt="Jotun Logo" class="h-10 w-auto object-contain">
                </div>
            </div>

            {{-- META INFO SECTION --}}
            <div class="grid grid-cols-2 gap-4 my-1.5 text-[10px] font-sans">
                <div class="space-y-0.5">
                    <div class="flex">
                        <span class="w-24 font-bold uppercase">INVOICE NO</span>
                        <span>: {{ $invoice->no_invoice }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-24 font-bold uppercase">TANGGAL</span>
                        <span>: {{ \Carbon\Carbon::parse($invoice->created_at)->format('d F Y') }}</span>
                    </div>
                </div>

                <div class="space-y-0.5 flex flex-col items-end">
                    <div class="w-full max-w-[240px] space-y-0.5">
                        <div class="flex items-start">
                            <span class="w-16 font-bold shrink-0">Kepada</span>
                            <div class="font-bold text-black uppercase leading-tight">
                                : {{ strtoupper($invoice->nama_klien) }}
                                @if($invoice->offer && $invoice->offer->client_details)
                                <div class="font-normal text-[10px] text-gray-800 uppercase leading-tight mt-0.5">{{ $invoice->offer->client_details }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="w-16 font-bold shrink-0">Telepon</span>
                            <span>: -</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-16 font-bold shrink-0">Sales</span>
                            <span class="font-bold">: YASRI</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MAIN TABLE (Tighter py-0.5) --}}
            <div class="my-1.5">
                <table class="w-full text-[10px] border-collapse">
                    <thead>
                        <tr class="border-t border-b border-black font-bold text-black uppercase">
                            <th class="py-0.5 px-1 text-center w-6">NO</th>
                            <th class="py-0.5 px-2 text-left">NAMA BARANG</th>
                            <th class="py-0.5 px-2 text-center w-20">JUMLAH</th>
                            <th class="py-0.5 px-2 text-center w-12">BONUS</th>
                            <th class="py-0.5 px-2 text-right w-20">@HARGA</th>
                            <th class="py-0.5 px-2 text-right w-20">HARGA</th>
                            <th class="py-0.5 px-2 text-center w-16">DISCOUNT</th>
                            <th class="py-0.5 px-2 text-right w-24">TOTAL</th>
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
                                    $consumption = $item->consumption_l > 0 ? $item->consumption_l : ($item->volume > 0 ? $item->volume : 1);
                                    $priceL = $item->price_per_liter > 0 ? $item->price_per_liter : $item->harga_per_m2;
                                    $rowTotal = $consumption * $priceL;
                                    
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
                                    @php
                                        $compB_subtotal = round($rowTotal * 0.09);
                                        $compA_subtotal = $rowTotal - $compB_subtotal;
                                        $compB_price = round($compB_subtotal / $qty);
                                        $compA_price = round($compA_subtotal / $qty);
                                    @endphp
                                    <tr class="align-top">
                                        <td class="py-0.5 px-1 text-center font-semibold">{{ $rowCounter++ }}.</td>
                                        <td class="py-0.5 px-2">
                                            <div class="flex justify-between items-center font-bold">
                                                <span>{{ strtoupper($namaCompA) }}</span>
                                                <span class="font-normal text-[9px] pr-2">{{ $packingA }}</span>
                                            </div>
                                        </td>
                                        <td class="py-0.5 px-2 text-center font-semibold">{{ number_format($qty, 0) }} CAN</td>
                                        <td class="py-0.5 px-2 text-center"></td>
                                        <td class="py-0.5 px-2 text-right">{{ number_format($compA_price, 0, ',', '.') }}</td>
                                        <td class="py-0.5 px-2 text-right">{{ number_format($compA_subtotal, 0, ',', '.') }}</td>
                                        <td class="py-0.5 px-2 text-center">- 0.00%</td>
                                        <td class="py-0.5 px-2 text-right font-semibold">{{ number_format($compA_subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-0.5 px-1 text-center font-semibold">{{ $rowCounter++ }}.</td>
                                        <td class="py-0.5 px-2">
                                            <div class="flex justify-between items-center font-bold">
                                                <span>{{ strtoupper($namaCompB) }}</span>
                                                <span class="font-normal text-[9px] pr-2">{{ $packingB }}</span>
                                            </div>
                                        </td>
                                        <td class="py-0.5 px-2 text-center font-semibold">{{ number_format($qty, 0) }} CAN</td>
                                        <td class="py-0.5 px-2 text-center"></td>
                                        <td class="py-0.5 px-2 text-right">{{ number_format($compB_price, 0, ',', '.') }}</td>
                                        <td class="py-0.5 px-2 text-right">{{ number_format($compB_subtotal, 0, ',', '.') }}</td>
                                        <td class="py-0.5 px-2 text-center">- 0.00%</td>
                                        <td class="py-0.5 px-2 text-right font-semibold">{{ number_format($compB_subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @else
                                    @php
                                        $pricePerCan = $qty > 0 ? round($rowTotal / $qty) : round($priceL * ($numTotal ?: 1));
                                    @endphp
                                    <tr class="align-top">
                                        <td class="py-0.5 px-1 text-center font-semibold">{{ $rowCounter++ }}.</td>
                                        <td class="py-0.5 px-2">
                                            <div class="flex justify-between items-center font-bold">
                                                <span>{{ strtoupper($item->nama_produk) }}</span>
                                                <span class="font-normal text-[9px] pr-2">{{ $packing }}</span>
                                            </div>
                                            @if(!empty($item->keterangan))
                                                <div class="text-[9px] font-normal text-gray-500 italic mt-0.5">Ket: {{ $item->keterangan }}</div>
                                            @endif
                                        </td>
                                        <td class="py-0.5 px-2 text-center font-semibold">{{ number_format($qty, 0) }} CAN</td>
                                        <td class="py-0.5 px-2 text-center"></td>
                                        <td class="py-0.5 px-2 text-right">{{ number_format($pricePerCan, 0, ',', '.') }}</td>
                                        <td class="py-0.5 px-2 text-right">{{ number_format($rowTotal, 0, ',', '.') }}</td>
                                        <td class="py-0.5 px-2 text-center">- 0.00%</td>
                                        <td class="py-0.5 px-2 text-right font-semibold">{{ number_format($rowTotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @elseif($invoice->offer && $invoice->offer->items->isNotEmpty())
                            @foreach($invoice->offer->items as $item)
                                @php
                                    $qty = $item->qty_order > 0 ? $item->qty_order : ($item->volume > 0 ? $item->volume : 1);
                                    $packing = $item->packing_size ?? '';
                                    $consumption = $item->consumption_l > 0 ? $item->consumption_l : ($item->volume > 0 ? $item->volume : 1);
                                    $priceL = $item->price_per_liter > 0 ? $item->price_per_liter : $item->harga_per_m2;
                                    $rowTotal = $consumption * $priceL;
                                    $numTotal = $parseNum($packing);
                                    $pricePerCan = $qty > 0 ? round($rowTotal / $qty) : round($priceL * ($numTotal ?: 1));
                                @endphp
                                <tr class="align-top">
                                    <td class="py-0.5 px-1 text-center font-semibold">{{ $rowCounter++ }}.</td>
                                    <td class="py-0.5 px-2">
                                        <div class="flex justify-between items-center font-bold">
                                            <span>{{ strtoupper($item->nama_produk) }}</span>
                                            <span class="font-normal text-[9px] pr-2">{{ $packing }}</span>
                                        </div>
                                        @if(!empty($item->keterangan))
                                            <div class="text-[9px] font-normal text-gray-500 italic mt-0.5">Ket: {{ $item->keterangan }}</div>
                                        @endif
                                    </td>
                                    <td class="py-0.5 px-2 text-center font-semibold">{{ number_format($qty, 0) }} CAN</td>
                                    <td class="py-0.5 px-2 text-center"></td>
                                    <td class="py-0.5 px-2 text-right">{{ number_format($pricePerCan, 0, ',', '.') }}</td>
                                    <td class="py-0.5 px-2 text-right">{{ number_format($rowTotal, 0, ',', '.') }}</td>
                                    <td class="py-0.5 px-2 text-center">- 0.00%</td>
                                    <td class="py-0.5 px-2 text-right font-semibold">{{ number_format($rowTotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @else
                            @php
                                $priceL = $invoice->total_penawaran;
                            @endphp
                            <tr class="align-top">
                                <td class="py-0.5 px-1 text-center font-semibold">1.</td>
                                <td class="py-0.5 px-2 font-bold">{{ strtoupper(optional($invoice->offer)->perihal ?? 'Total Pekerjaan / Supply Cat (sesuai Penawaran)') }}</td>
                                <td class="py-0.5 px-2 text-center font-semibold">1 PAKET</td>
                                <td class="py-0.5 px-2 text-center"></td>
                                <td class="py-0.5 px-2 text-right">{{ number_format($priceL, 0, ',', '.') }}</td>
                                <td class="py-0.5 px-2 text-right">{{ number_format($priceL, 0, ',', '.') }}</td>
                                <td class="py-0.5 px-2 text-center">- 0.00%</td>
                                <td class="py-0.5 px-2 text-right font-semibold">{{ number_format($priceL, 0, ',', '.') }}</td>
                            </tr>
                        @endif

                        @foreach($invoice->additions as $addition)
                        <tr class="align-top">
                            <td class="py-0.5 px-1 text-center font-semibold">{{ $rowCounter++ }}.</td>
                            <td class="py-0.5 px-2 font-bold">{{ strtoupper($addition->nama_pekerjaan) }}</td>
                            <td class="py-0.5 px-2 text-center font-semibold">1 Ls</td>
                            <td class="py-0.5 px-2 text-center"></td>
                            <td class="py-0.5 px-2 text-right">{{ number_format($addition->harga, 0, ',', '.') }}</td>
                            <td class="py-0.5 px-2 text-right">{{ number_format($addition->harga, 0, ',', '.') }}</td>
                            <td class="py-0.5 px-2 text-center">- 0.00%</td>
                            <td class="py-0.5 px-2 text-right font-semibold">{{ number_format($addition->harga, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="border-b border-black w-full mt-0.5"></div>
            </div>

            @php
                if ($invoice->offer && $invoice->offer->items->isNotEmpty()) {
                    $calc_total_penawaran = 0;
                    foreach($invoice->offer->items as $it) {
                        $c = $it->consumption_l > 0 ? $it->consumption_l : ($it->volume > 0 ? $it->volume : 1);
                        $p = $it->price_per_liter > 0 ? $it->price_per_liter : $it->harga_per_m2;
                        $calc_total_penawaran += ($c * $p);
                    }
                } else {
                    $calc_total_penawaran = $invoice->total_penawaran > 0 ? $invoice->total_penawaran : 0;
                }
                $calc_grand_total = ($calc_total_penawaran + $invoice->total_tambahan) - $invoice->diskon;
                $calc_sisa = $calc_grand_total - $invoice->total_dp;
            @endphp

            {{-- SUMMARY & NOTES SECTION --}}
            <div class="flex justify-between gap-4 text-[10px] font-sans my-1.5">
                <div class="w-7/12 space-y-0.5 pr-2">
                    <div class="flex items-center gap-6">
                        <div><span class="font-bold uppercase">CREDIT TERM :</span> 0 hari</div>
                        <div><span class="font-bold uppercase">JATUH TEMPO :</span> {{ \Carbon\Carbon::parse($invoice->created_at)->format('d F Y') }}</div>
                    </div>

                    <div>
                        <span class="font-bold">Catatan :</span> {{ strtoupper($invoice->nama_klien) }} {{ $invoice->offer && $invoice->offer->client_details ? '- ' . strtoupper($invoice->offer->client_details) : '' }}
                    </div>

                    <div>
                        <span class="font-bold">Terbilang :</span> {{ terbilang_rupiah_clean($calc_grand_total) }}
                    </div>

                    <div class="text-[9px] text-black pt-0.5">
                        <span class="font-bold">Printed By :</span> Admin, {{ date('H:i:s, l, d F Y') }}
                    </div>
                </div>

                <div class="w-5/12 text-right space-y-0.5 font-medium min-w-[240px]">
                    <div class="flex justify-between items-center whitespace-nowrap">
                        <span class="font-bold">TOTAL</span>
                        <span class="font-medium ml-4">: IDR {{ number_format($calc_total_penawaran + $invoice->total_tambahan, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center whitespace-nowrap">
                        <span class="font-bold">DISCOUNT</span>
                        <span class="font-medium ml-4">: IDR {{ $invoice->diskon > 0 ? '- ' . number_format($invoice->diskon, 0, ',', '.') : '-' }}</span>
                    </div>

                    <div class="flex justify-between items-center whitespace-nowrap">
                        <span class="font-bold">PPN</span>
                        <span class="font-medium ml-4">: IDR -</span>
                    </div>

                    <div class="border-t border-black my-0.5"></div>

                    <div class="flex justify-between items-center whitespace-nowrap text-[11px] font-bold">
                        <span class="font-extrabold">GRAND TOTAL</span>
                        <span class="font-extrabold ml-4">: IDR {{ number_format($calc_grand_total, 0, ',', '.') }}</span>
                    </div>

                    @if($invoice->payments && $invoice->payments->count() > 0)
                        @foreach($invoice->payments as $payment)
                        <div class="flex justify-between items-center whitespace-nowrap">
                            <span class="font-semibold">{{ strtoupper($payment->keterangan) }}</span>
                            <span class="font-medium ml-4">: IDR - {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        <div class="flex justify-between items-center whitespace-nowrap text-[11px] font-bold text-blue-900">
                            <span class="font-extrabold">SISA PEMBAYARAN</span>
                            <span class="font-extrabold ml-4">: IDR {{ number_format($calc_sisa, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- SIGNATURE SECTION (INVOICE) --}}
            <div class="mt-auto grid grid-cols-4 text-center text-[10px] font-sans gap-6">
                <div class="flex flex-col justify-between h-14">
                    <p class="font-normal text-black">Yang Menerima,</p>
                    <div class="border-b border-black w-full"></div>
                </div>
                <div class="flex flex-col justify-between h-14">
                    <p class="font-normal text-black">Kepala Gudang,</p>
                    <div class="border-b border-black w-full"></div>
                </div>
                <div class="flex flex-col justify-between h-14">
                    <p class="font-normal text-black">Supir/Helper,</p>
                    <div class="border-b border-black w-full"></div>
                </div>
                <div class="flex flex-col justify-between h-14">
                    <p class="font-normal text-black">Hormat kami,</p>
                    <div class="border-b border-black w-full"></div>
                </div>
            </div>
        </div>


        {{-- ========================================================================= --}}
        {{-- MIDDLE SEPARATOR: CLEAN DASHED LINE (NO TEXT) --}}
        {{-- ========================================================================= --}}
        <div class="my-2 border-b border-dashed border-gray-400 no-print"></div>


        {{-- ========================================================================= --}}
        {{-- SECTION 2: SURAT JALAN (BOTTOM HALF) --}}
        {{-- ========================================================================= --}}
        <div class="surat-jalan-section pt-1 flex-1 flex flex-col">
            {{-- HEADER SECTION --}}
            <div class="flex justify-between items-start pb-1 gap-2">
                <div class="w-5/12 leading-snug text-black">
                    <h2 class="font-extrabold text-xs text-black tracking-tight">PT. TASNIEM GERAI INSPIRASI</h2>
                    <p>Komp.Ruko KDA Junction Blok C 8-9</p>
                    <p>Batam Centre</p>
                    <div class="mt-1 space-y-0.5 text-[10px]">
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

                <div class="w-3/12 text-center pt-1">
                    <h1 class="text-base font-extrabold tracking-wider uppercase underline">SURAT JALAN</h1>
                </div>

                <div class="w-4/12 flex flex-col items-end">
                    <div class="w-full max-w-[240px] space-y-0.5 text-[10px]">
                        <div class="flex items-start">
                            <span class="w-16 font-bold shrink-0">Kepada</span>
                            <div class="font-bold text-black uppercase leading-tight">
                                : {{ strtoupper($invoice->nama_klien) }}
                                @if($invoice->offer && $invoice->offer->client_details)
                                <div class="font-normal text-[10px] text-gray-800 uppercase leading-tight mt-0.5">{{ $invoice->offer->client_details }}</div>
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
                            <span class="font-bold">: YASRI</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MAIN TABLE (Tighter py-0.5) --}}
            <div class="my-1.5">
                <table class="w-full text-[10px] border-collapse">
                    <thead>
                        <tr class="border-t border-b border-black font-bold text-black uppercase">
                            <th class="py-0.5 px-1 text-center w-8">NO</th>
                            <th class="py-0.5 px-2 text-left">NAMA BARANG</th>
                            <th class="py-0.5 px-2 text-center w-28">JUMLAH</th>
                            <th class="py-0.5 px-2 text-center w-20">BONUS</th>
                            <th class="py-0.5 px-2 text-center w-40">KETERANGAN</th>
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
                                    <tr class="align-top">
                                        <td class="py-0.5 px-1 text-center font-semibold">{{ $rowCounter++ }}.</td>
                                        <td class="py-0.5 px-2">
                                            <div class="flex justify-between items-center font-bold">
                                                <span>{{ strtoupper($namaCompA) }}</span>
                                                <span class="font-normal text-[9px] pr-4">{{ $packingA }}</span>
                                            </div>
                                        </td>
                                        <td class="py-0.5 px-2 text-center font-semibold">{{ number_format($qty, 0) }} CAN</td>
                                        <td class="py-0.5 px-2 text-center"></td>
                                        <td class="py-0.5 px-2 text-center"></td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-0.5 px-1 text-center font-semibold">{{ $rowCounter++ }}.</td>
                                        <td class="py-0.5 px-2">
                                            <div class="flex justify-between items-center font-bold">
                                                <span>{{ strtoupper($namaCompB) }}</span>
                                                <span class="font-normal text-[9px] pr-4">{{ $packingB }}</span>
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
                                                <span class="font-normal text-[9px] pr-4">{{ $packing }}</span>
                                            </div>
                                            @if(!empty($item->keterangan))
                                                <div class="text-[9px] font-normal text-gray-500 italic mt-0.5">Ket: {{ $item->keterangan }}</div>
                                            @endif
                                        </td>
                                        <td class="py-0.5 px-2 text-center font-semibold">{{ number_format($qty, 0) }} CAN</td>
                                        <td class="py-0.5 px-2 text-center"></td>
                                        <td class="py-0.5 px-2 text-center"></td>
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
                                            <span class="font-normal text-[9px] pr-4">{{ $packing }}</span>
                                        </div>
                                        @if(!empty($item->keterangan))
                                            <div class="text-[9px] font-normal text-gray-500 italic mt-0.5">Ket: {{ $item->keterangan }}</div>
                                        @endif
                                    </td>
                                    <td class="py-0.5 px-2 text-center font-semibold">{{ number_format($qty, 0) }} CAN</td>
                                    <td class="py-0.5 px-2 text-center"></td>
                                    <td class="py-0.5 px-2 text-center"></td>
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
                </div>
                <div class="text-[9px] text-black pt-0.5">
                    <span class="font-bold">Printed By :</span> Admin, {{ date('H:i:s, l, d F Y') }}
                </div>
            </div>

            {{-- SIGNATURE SECTION (SURAT JALAN) --}}
            <div class="mt-auto grid grid-cols-4 text-center text-[10px] font-sans gap-6">
                <div class="flex flex-col justify-between h-14">
                    <p class="font-normal text-black">Bag. Administrasi,</p>
                    <div class="border-b border-black w-full"></div>
                </div>
                <div class="flex flex-col justify-between h-14">
                    <p class="font-normal text-black">Kepala Gudang,</p>
                    <div class="border-b border-black w-full"></div>
                </div>
                <div class="flex flex-col justify-between h-14">
                    <p class="font-normal text-black">Supir/Helper,</p>
                    <div class="border-b border-black w-full"></div>
                </div>
                <div class="flex flex-col justify-between h-14">
                    <p class="font-normal text-black">Yang Menerima,</p>
                    <div class="border-b border-black w-full"></div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>
