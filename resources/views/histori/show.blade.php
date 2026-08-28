@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">

    <div class="max-w-4xl mx-auto mb-4 flex justify-between gap-2 print:hidden">
        <a href="{{ route('histori.index') }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded hover:bg-gray-300 transition-colors">
            &larr; Kembali
        </a>
        <div class="flex gap-2">
            <a href="{{ route('histori.print', $offer->id) }}" target="_blank" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition-colors shadow-sm inline-flex items-center gap-2">
                🖨️ Print Surat (PDF)
            </a>
            <a href="{{ route('invoice.create_from_offer', $offer->id) }}" class="bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700 transition-colors shadow-sm">
                Buat Invoice &rarr;
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 shadow-lg rounded-lg" id="surat-penawaran">

        {{-- HEADER KOP SURAT --}}
        <header class="w-full mb-6">
            <div class="w-full">
                <img src="{{ asset('images/kopsurat.jpg') }}" alt="Kop Surat PT Tasniem Gerai Inspirasi" class="w-full h-auto">
            </div>
        </header>

        <section class="mb-6 text-sm sans flex justify-between items-start">
            @php
            $bulanRomawi = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
            $romawi = $bulanRomawi[$offer->created_at->format('n')];
            $tahun = $offer->created_at->format('Y');
            $seq     = str_pad(10132 + $offer->id, 7, '0', STR_PAD_LEFT);
            $noSurat = $offer->no_surat ?? ($seq . "/SP/TGI-1/" . $romawi . "/" . $tahun);
            @endphp
            <div>
                <p class="font-semibold text-gray-800">Perihal : {{ $offer->perihal ?? 'Penawaran Quotation Produk' }}</p>
                <p class="font-mono font-bold text-blue-700">Nomor : {{ $noSurat }}</p>
                @if($offer->project_no)
                <p class="font-semibold text-gray-700">Project No : <span class="font-bold text-gray-900">{{ $offer->project_no }}</span></p>
                @endif
            </div>
            <div class="text-right">
                <p>Batam, {{ $offer->created_at->format('d F Y') }}</p>
            </div>
        </section>

        <section class="mt-6">
            <p class="text-gray-600">Kepada Yth,</p>
            <h3 class="text-md font-bold text-gray-800">{{ $offer->nama_klien }}</h3>
            @if($offer->client_details)
            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $offer->client_details }}</p>
            @endif
            <p class="text-gray-700 mt-2">Dengan Hormat,</p>
        </section>

        <section class="mt-4 space-y-4 text-sm text-gray-700 leading-relaxed">
            <p>Kami PT. TASNIEM GERAI INSPIRASI adalah dealer resmi PT. JOTUN INDONESIA dan toko pertama Jotun Flagship terbesar di Kota Batam yang merupakan retail Supply Cat Jotun Dekoratif & Industrial. Berikut kami sampaikan rincian penawaran harga (Quotation):</p>
        </section>

        {{-- PHP LOGIC --}}
        @php
        $showTotal = !$offer->hilangkan_grand_total;
        $totalJasa = $offer->jasaItems->sum('harga_jasa');
        @endphp

        <section class="mt-8">
            <div class="w-full overflow-x-auto">

                @if($offer->items->isNotEmpty())
                @if($offer->jenis_penawaran == 'proyek')
                {{-- TABEL QUOTATION PROYEK --}}
                <table class="w-full text-left border-collapse page-break-inside-avoid text-xs mb-4">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs w-8">No</th>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs">Area Pekerjaan</th>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs">Produk</th>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs text-right">Volume</th>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs text-right">Harga Satuan</th>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($offer->items as $index => $item)
                        @php
                            $subtotal = $item->volume * $item->harga_per_m2;
                            $compB = $item->comp_b ?? $item->product?->comp_b ?? \App\Models\Product::where('nama_produk', $item->nama_produk)->first()?->comp_b;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-2 text-gray-600 align-middle text-center">{{ $index + 1 }}</td>
                            <td class="py-2 px-2 text-gray-700 align-middle">{{ $item->area_dinding ?: '-' }}</td>
                            <td class="py-2 px-2 text-gray-900 font-bold align-middle">
                                <div>{{ $item->nama_produk }}</div>
                                @if($offer->tampilkan_comp_b && !empty($compB))
                                    <div class="text-[11px] font-normal text-blue-600 mt-0.5">Comp B: {{ $compB }}</div>
                                @endif
                            </td>
                            <td class="py-2 px-2 text-gray-700 text-right align-middle">{{ $item->volume }}</td>
                            <td class="py-2 px-2 text-gray-700 text-right align-middle">Rp {{ number_format($item->harga_per_m2, 0, ',', '.') }}</td>
                            <td class="py-2 px-2 text-gray-900 font-extrabold text-right align-middle">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                {{-- TABEL QUOTATION PRODUK --}}
                <table class="w-full text-left border-collapse page-break-inside-avoid text-xs mb-4">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs w-8">No</th>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs">Nama Produk</th>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs">Packing Size</th>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs text-right">Qty Order</th>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs text-right">Consumption (L)</th>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs text-center">Status</th>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs text-right">Price/L (+40%)</th>
                            <th class="py-2.5 px-2 font-semibold uppercase text-xs text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($offer->items as $index => $item)
                        @php
                            $qty = $item->qty_order > 0 ? $item->qty_order : 1;
                            $consumption = $item->consumption_l > 0 ? $item->consumption_l : ($item->volume > 0 ? $item->volume : 1);
                            $priceL = $item->price_per_liter > 0 ? $item->price_per_liter : $item->harga_per_m2;
                            $subtotal = $consumption * $priceL;
                            $compB = $item->comp_b ?? $item->product?->comp_b ?? \App\Models\Product::where('nama_produk', $item->nama_produk)->first()?->comp_b;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-2 text-gray-600 align-middle text-center">{{ $index + 1 }}</td>
                            <td class="py-2 px-2 text-gray-900 font-bold align-middle">
                                <div>{{ $item->nama_produk }}</div>
                                @if($offer->tampilkan_comp_b && !empty($compB))
                                    <div class="text-[11px] font-normal text-blue-600 mt-0.5">Comp B: {{ $compB }}</div>
                                @endif
                            </td>
                            <td class="py-2 px-2 text-gray-700 align-middle">{{ $item->packing_size ?: '-' }}</td>
                            <td class="py-2 px-2 text-gray-700 text-right align-middle font-semibold">{{ $qty + 0 }}</td>
                            <td class="py-2 px-2 text-gray-700 text-right font-bold align-middle text-blue-700">{{ $consumption + 0 }} L</td>
                            <td class="py-2 px-2 align-middle text-center">
                                <span class="px-2 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800">
                                    {{ $item->status_produk ?: 'READY' }}
                                </span>
                            </td>
                            <td class="py-2 px-2 text-gray-700 text-right align-middle">Rp {{ number_format($priceL, 0, ',', '.') }}</td>
                            <td class="py-2 px-2 text-gray-900 font-extrabold text-right align-middle">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                @endif

                {{-- TABEL JASA (DENGAN TOTAL) --}}
                @if($offer->jasaItems->isNotEmpty())
                <div class="mt-4 page-break-inside-avoid">
                    <h4 class="font-bold text-gray-800 mb-2 uppercase border-b-2 border-gray-800 inline-block text-sm">Pengerjaan Tambahan</h4>
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="py-2 px-1 font-semibold uppercase text-xs w-[50%] align-middle" colspan="3">Deskripsi Pengerjaan</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[10%] align-middle">Vol/Sat</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[20%] align-middle">Harga Satuan</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[20%] align-middle">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offer->jasaItems as $jasa)
                            <tr class="border-b border-gray-200">
                                <td class="py-1 px-1 font-medium text-gray-800 text-xs leading-none align-middle" colspan="3">{{ $jasa->nama_jasa }}</td>
                                <td class="py-1 px-1 text-right text-xs leading-none align-middle whitespace-nowrap">{{ $jasa->volume + 0 }} {{ $jasa->satuan }}</td>
                                <td class="py-1 px-1 text-xs leading-none whitespace-nowrap align-middle">
                                    <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($jasa->harga_satuan ?? ($jasa->harga_jasa / ($jasa->volume ?: 1)), 0, ',', '.') }}</span></div>
                                </td>
                                <td class="py-1 px-1 text-xs leading-none whitespace-nowrap font-bold text-gray-900 align-middle">
                                    <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($jasa->harga_jasa, 0, ',', '.') }}</span></div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        {{-- INI TOTAL PENGERJAAN TAMBAHAN YANG ANDA MINTA --}}
                        @if($showTotal)
                        <tfoot>
                            <tr class="bg-gray-100 font-bold text-gray-800">
                                <td colspan="5" class="py-1 px-1 text-xs text-right uppercase align-middle">Total Pengerjaan Tambahan</td>
                                <td class="py-1 px-1 text-xs text-right whitespace-nowrap align-middle">
                                    <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($totalJasa, 0, ',', '.') }}</span></div>
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
                @endif

            </div>
        </section>

        @if($showTotal)
        <section class="mt-4 flex justify-end" id="grand-total-block">
            <div class="w-full md:w-6/12">
                <div class="flex justify-between items-center bg-gray-800 text-white p-3 rounded-lg">
                    <span class="text-lg font-bold uppercase">Grand Total</span>
                    <span class="text-xl font-bold whitespace-nowrap flex gap-2">
                        <span>Rp</span>
                        <span>{{ number_format($offer->total_keseluruhan, 0, ',', '.') }}</span>
                    </span>
                </div>
            </div>
        </section>
        @endif

        <section class="mt-8 text-sm text-gray-700 leading-relaxed">
            <h4 class="font-semibold text-gray-800">Teknis pengerjaan:</h4>
            <ul class="list-disc list-inside ml-4 mt-2">
                <li>Semua peralatan pekerjaan akan disiapkan oleh pihak PT. Tasniem Gerai Inspirasi</li>
                <li>Perbaikan dan Dempul retakan tembok area pengerjaan</li>
                <li>Cleaning area sebelum melakukan pekerjaan</li>
                <li>Pengaplikasikan Cat Dasar (Sealer)</li>
                <li>Pengaplikasikan Topcoat minimal 2 kali lapis</li>
                <li>Finish.</li>
            </ul>
        </section>

        <section class="mt-8 text-sm text-gray-700 leading-relaxed">
            <h4 class="font-semibold text-gray-800">Beberapa Hal yang perlu kami sampaikan sebelum pengerjaan :</h4>
            <ul class="list-disc list-inside ml-4 mt-2">
                <li>Permohonan untuk Air, Listrik dan Gudang peyimpanan alat-alat kerja di siapkan oleh Pemberi Kerja</li>
                <li>Down Payment minimal 30% dibayarkan sebelum pekerjaan di mulai</li>
                <li>Payment kedua sebesar 30 %dibayarkan pada saat pengerjaan berlangsung</li>
                <li>Pelunasan sebesar 40% di bayarkan setelah Pengerjaan selesai dan telah di lakukan pengecekan Bersama</li>
                <li>Harga penawaran diatas berlaku 30 hari sejak tanggal surat penawaran di tebitkan</li>
            </ul>
        </section>


        <section class="text-md font-bold text-gray-800">
            <p>NB : Surat Ini Berlaku Sampai dengan Tanggal {{ $offer->created_at->copy()->addDays(30)->format('d F Y') }}.</p>
        </section>

        <section class="mt-6 text-sm text-gray-700">
            <p>Demikianlah surat penawaran ini kami sampaikan, semoga dapat disetujui.</p>
        </section>

        <section class="mt-10 flex justify-end page-break-inside-avoid">
            <div class="text-center">
                <p>Hormat kami,</p>
                <div class="h-28 w-48 relative">
                    <img src="{{ asset('images/ttd.png') }}" alt="Logo & Tanda Tangan" class="h-28 opacity-100 mx-auto">
                </div>
                <p class="font-bold text-gray-800">SAMSU RIZAL</p>
                <p class="text-gray-600">General Manager</p>
            </div>
        </section>

    </div>
</div>

<style>
    @media print {
        @page {
            size: A4;
            margin: 1.5cm;
            /* Margin yang cukup */
        }

        /* Hilangkan elemen yang tidak perlu */
        .print\:hidden,
        nav,
        header.bg-white.shadow-sm,
        aside,
        .bg-gray-200 {
            display: none !important;
        }

        /* Atur Body */
        body {
            background-color: white;
            margin: 0;
            padding: 0;
            overflow: visible !important;
            /* PENTING: Agar bisa scroll/print halaman selanjutnya */
        }

        /* Atur Kontainer Utama */
        .container {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Atur Kertas Surat */
        #surat-penawaran {
            width: 100% !important;
            max-width: 100% !important;
            box-shadow: none !important;
            border: none !important;
            margin: 0 !important;
            padding: 0 !important;
            position: relative !important;
            /* PENTING: Gunakan relative/static, BUKAN absolute */
            left: auto !important;
            top: auto !important;
        }

        /* Warna Hitam untuk Teks */
        #grand-total-block div,
        #grand-total-block span {
            color: #000 !important;
            background-color: transparent !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Page Break Management */
        .page-break-inside-avoid {
            page-break-inside: avoid;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-row-group;
        }
    }
</style>
@endsection