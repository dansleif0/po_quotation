@extends('layouts.app')

@section('content')

@php
    $bulanIndo = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $bulanRomawi = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
    
    $dateObj = $offer->created_at ?? now();
    $tglStr = $dateObj->format('j') . ' ' . ($bulanIndo[$dateObj->format('n')] ?? $dateObj->format('F')) . ' ' . $dateObj->format('Y');
    
    $romawi = $bulanRomawi[$dateObj->format('n')];
    $tahun  = $dateObj->format('Y');
    $seq    = str_pad(10132 + $offer->id, 7, '0', STR_PAD_LEFT);
    $noSurat = $offer->no_surat ?? ($seq . "/SP/TGI-1/" . $romawi . "/" . $tahun);
@endphp

<div class="container mx-auto my-6 px-4 font-serif">

    {{-- Top Action Buttons (Hidden on Print) --}}
    <div class="max-w-4xl mx-auto mb-4 flex justify-between items-center print:hidden">
        <a href="{{ route('histori.index') }}" class="inline-flex items-center gap-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2.5 px-5 rounded-xl transition text-sm">
            &larr; Kembali ke Histori Quotation
        </a>
        <div class="flex gap-3">
            <a href="{{ route('histori.print', $offer->id) }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl transition shadow-md text-sm cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak / Download PDF
            </a>
            <a href="{{ route('invoice.create_from_offer', $offer->id) }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-xl transition shadow-md text-sm cursor-pointer">
                Buat Invoice &rarr;
            </a>
        </div>
    </div>

    {{-- Document Paper Container --}}
    <div class="max-w-4xl mx-auto bg-white shadow-xl border border-slate-200 rounded-sm text-black print:shadow-none print:border-none print:p-0" id="surat-quotation">
        
        <style>
            #surat-quotation {
                font-family: 'Times New Roman', Times, serif;
                color: #000000;
                line-height: 1.4;
                padding: 12.7mm; /* Narrow Margin (0.5 in) */
            }
            #surat-quotation table {
                border-collapse: collapse;
                width: 100%;
            }
            #surat-quotation th, #surat-quotation td {
                border: 1px solid #000000;
                padding: 5px 8px;
            }
            @media print {
                @page { 
                    size: A4; 
                    margin: 12.7mm; /* Narrow Margin (0.5 in) */
                }
                body * { visibility: hidden; }
                #surat-quotation, #surat-quotation * { visibility: visible; }
                #surat-quotation {
                    position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0;
                    box-shadow: none !important; border: none !important; background-color: white !important;
                }
                .print\:hidden { display: none !important; }
            }
        </style>

        {{-- Official Kop Surat Header --}}
        <div class="w-full mb-5">
            <img src="{{ asset('images/kopsurat.jpg') }}" alt="Kop Surat PT Tasniem Gerai Inspirasi" class="w-full h-auto object-contain">
        </div>

        {{-- Top Header Date & Letter No --}}
        <div class="text-base mb-5">
            <p>Batam, {{ $tglStr }}</p>
            <p>Nomor. : {{ $noSurat }}</p>
        </div>

        {{-- Recipient Information --}}
        <div class="text-base mb-5">
            <p>Kepada Yth,</p>
            <p class="font-bold uppercase mt-1.5 text-lg">{{ $offer->nama_klien }}</p>
            @if($offer->client_details)
                <p class="text-sm text-gray-800 whitespace-pre-line">{{ $offer->client_details }}</p>
            @endif
            <p class="mt-2">Dengan Hormat,</p>
        </div>

        {{-- Introduction Body Text --}}
        <div class="text-base space-y-2.5 mb-5 text-justify">
            <p>
                Kami PT. TASNIEM GERAI INSPIRASI adalah dealer resmi resmi PT. JOTUN INDONESIA, didirikan pada tanggal 4 Februari 2010, Konsep Inspirasi Centre pertama di kota Batam dan pertama di Indonesia, website <a href="https://tasniemgroup.com" target="_blank" class="text-blue-700 underline">https://tasniemgroup.com</a>.
            </p>
            <div>
                <p>Kami PT Tasniem Gerai Inspirasi bergerak di bidang Painting Dan Pekerjaan Sipil lainnya :</p>
                <ol class="list-decimal list-inside ml-2 space-y-0.5 mt-1">
                    <li>Pekerjaan pengecatan dan perawatan gedung</li>
                    <li>Pemasangan partisi dan plafon Finising gypsum dan plafon sunda Plafon</li>
                    <li>Pekerjaan Pengecatan Lantai epoxy</li>
                </ol>
            </div>
            <div>
                <p>Dengan ini kami sampaikan penawaran Harga cat Jotun :</p>
                <p class="font-bold mt-1">Project NO : {{ $offer->project_no ?: 'HYDRATE' }}</p>
            </div>
        </div>

        {{-- Quotation Items Table --}}
        <div class="my-5 overflow-x-auto">
            <table class="w-full text-xs text-black border border-black">
                <thead>
                    <tr class="bg-gray-100 font-bold text-center">
                        <th class="w-8 border border-black py-2 px-1">NO</th>
                        <th class="border border-black py-2 px-2 text-left min-w-[180px]">Product</th>
                        <th class="w-24 border border-black py-2 px-1 text-center">Packing<br>Size (L)</th>
                        <th class="w-20 border border-black py-2 px-1 text-center">Qty<br>Order</th>
                        <th class="w-24 border border-black py-2 px-1 text-center">Consumption<br>(L)</th>
                        <th class="w-28 border border-black py-2 px-1 text-center">Product</th>
                        <th class="w-28 border border-black py-2 px-2 text-right">Price per (L)</th>
                        <th class="w-32 border border-black py-2 px-2 text-right">Total price</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $sumTotal = 0; 
                        $rowCounter = 1;
                    @endphp
                    @foreach($offer->items as $idx => $item)
                    @php
                        $qty = $item->qty_order > 0 ? $item->qty_order : 1;
                        $consumption = $item->consumption_l > 0 ? $item->consumption_l : ($item->volume > 0 ? $item->volume : 1);
                        $priceL = $item->price_per_liter > 0 ? $item->price_per_liter : $item->harga_per_m2;
                        $rowTotal = $consumption * $priceL;
                        $sumTotal += $rowTotal;
                        $compB = $item->comp_b ?? $item->product?->comp_b ?? \App\Models\Product::where('nama_produk', $item->nama_produk)->first()?->comp_b;
                    @endphp

                    @if($offer->tampilkan_comp_b && !empty($compB))
                        @php
                            $compB_subtotal = round($rowTotal * 0.09);
                            $compA_subtotal = $rowTotal - $compB_subtotal;

                            $compB_price = round($priceL * 0.09);
                            $compA_price = $priceL - $compB_price;
                            $prod = $item->product ?? \App\Models\Product::where('nama_produk', $item->nama_produk)->first();
                            $packingB = $prod?->packing_size_b ?? $item->packing_size_b ?? $item->packing_size;
                        @endphp
                        {{-- Row 1: Main Product (Comp A) --}}
                        <tr>
                            <td class="text-center border border-black py-1.5 px-1 align-middle">{{ $rowCounter++ }}</td>
                            <td class="font-bold uppercase border border-black py-1.5 px-2 align-middle">
                                <div>{{ $item->nama_produk }}</div>
                            </td>
                            <td class="text-center border border-black py-1.5 px-1 align-middle">{{ $item->packing_size ?: '-' }}</td>
                            <td class="text-center border border-black py-1.5 px-1 align-middle">{{ $qty + 0 }}</td>
                            <td class="text-center border border-black py-1.5 px-1 align-middle">{{ $consumption + 0 }}</td>
                            <td class="text-center uppercase border border-black py-1.5 px-1 align-middle">{{ $item->status_produk ?: 'READY' }}</td>
                            <td class="text-right border border-black py-1.5 px-2 align-middle">{{ number_format($compA_price, 0, ',', '.') }}</td>
                            <td class="text-right border border-black py-1.5 px-2 align-middle font-semibold">{{ number_format($compA_subtotal, 0, ',', '.') }}</td>
                        </tr>
                        {{-- Row 2: Comp B --}}
                        <tr>
                            <td class="text-center border border-black py-1.5 px-1 align-middle">{{ $rowCounter++ }}</td>
                            <td class="font-bold uppercase border border-black py-1.5 px-2 align-middle text-gray-800">
                                <div>{{ $compB }}</div>
                            </td>
                            <td class="text-center border border-black py-1.5 px-1 align-middle">{{ $packingB ?: '-' }}</td>
                            <td class="text-center border border-black py-1.5 px-1 align-middle">{{ $qty + 0 }}</td>
                            <td class="text-center border border-black py-1.5 px-1 align-middle">{{ $consumption + 0 }}</td>
                            <td class="text-center uppercase border border-black py-1.5 px-1 align-middle">{{ $item->status_produk ?: 'READY' }}</td>
                            <td class="text-right border border-black py-1.5 px-2 align-middle">{{ number_format($compB_price, 0, ',', '.') }}</td>
                            <td class="text-right border border-black py-1.5 px-2 align-middle font-semibold">{{ number_format($compB_subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        {{-- Single Normal Row --}}
                        <tr>
                            <td class="text-center border border-black py-1.5 px-1 align-middle">{{ $rowCounter++ }}</td>
                            <td class="font-bold uppercase border border-black py-1.5 px-2 align-middle">
                                <div>{{ $item->nama_produk }}</div>
                            </td>
                            <td class="text-center border border-black py-1.5 px-1 align-middle">{{ $item->packing_size ?: '-' }}</td>
                            <td class="text-center border border-black py-1.5 px-1 align-middle">{{ $qty + 0 }}</td>
                            <td class="text-center border border-black py-1.5 px-1 align-middle">{{ $consumption + 0 }}</td>
                            <td class="text-center uppercase border border-black py-1.5 px-1 align-middle">{{ $item->status_produk ?: 'READY' }}</td>
                            <td class="text-right border border-black py-1.5 px-2 align-middle">{{ number_format($priceL, 0, ',', '.') }}</td>
                            <td class="text-right border border-black py-1.5 px-2 align-middle font-semibold">{{ number_format($rowTotal, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    @endforeach
                    @if($offer->diskon_global > 0)
                    <tr>
                        <td colspan="7" class="text-right border border-black py-1.5 px-2 font-bold">Subtotal</td>
                        <td class="text-right border border-black py-1.5 px-2 font-bold">{{ number_format($sumTotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right border border-black py-1.5 px-2 font-bold text-red-600">Diskon Global</td>
                        <td class="text-right border border-black py-1.5 px-2 font-bold text-red-600">- {{ number_format($offer->diskon_global, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="font-bold">
                        <td colspan="7" class="border border-black py-2 px-2 text-left font-bold text-xs">
                            Note : Quantity will be adjusted to Jotun standard packing size
                        </td>
                        <td class="border border-black py-2 px-2 text-right font-extrabold text-sm">
                            {{ number_format($offer->diskon_global > 0 ? max(0, $sumTotal - $offer->diskon_global) : ($offer->total_keseluruhan ?: $sumTotal), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Sales Condition Section --}}
        <div class="text-sm my-5">
            <p class="font-semibold mb-1">Sales Condition :</p>
            <ul class="space-y-0.5 ml-2">
                <li>- Above Prices are Franco Batam</li>
                <li>- Above Prices are include Discount</li>
                <li>- Above Prices are Exclude PPn 11% ( Free PPn Valid For Batam, Bintan, & Karimun area )</li>
                <li>- Additional Surcharge ( Boat, Agent, Crane, etc ) will be taken By Customer</li>
                <li>- Coating Advisor Rate Per Day USD 150 (Free of Charge for this project 7-14 days)</li>
                <li>- Working days 2-3 days after PO Received for Available Stock</li>
                <li>- Working days 3-4 Weeks Working Days for Special Products Made to Order (MTO)</li>
                <li>- Min 100 Litres For Free Delivery order</li>
                <li>- Payment Term : 30 Days</li>
            </ul>
        </div>

        {{-- Closing Statement --}}
        <div class="text-base my-5">
            <p>Demikianlah surat penawaran ini kami sampaikan, semoga dapat disetujui.</p>
        </div>

        {{-- Signature Block --}}
        <div class="mt-8 flex justify-end text-base">
            <div class="text-center w-64">
                <p>Hormat kami,</p>
                <div class="h-20 flex items-center justify-center my-2">
                    @if(file_exists(public_path('images/ttd.png')))
                        <img src="{{ asset('images/ttd.png') }}" alt="Tanda Tangan" class="h-20 object-contain">
                    @else
                        <div class="h-16"></div>
                    @endif
                </div>
                <p class="font-bold underline uppercase text-base">SAMSU RIZAL</p>
                <p class="text-sm">General Manager</p>
            </div>
        </div>

    </div>
</div>
@endsection