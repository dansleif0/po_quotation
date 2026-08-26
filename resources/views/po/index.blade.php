@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Top Header Banner --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Histori Purchase Order (PO)</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar riwayat Purchase Order (PO) resmi dari klien & transaksi perusahaan.</p>
        </div>

        <a href="{{ route('po.create') }}"
            style="background-color: #2563eb !important; color: #ffffff !important;"
            class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-blue-500/20 flex items-center gap-2 cursor-pointer shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current text-white" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            <span>+ Tambah PO Baru</span>
        </a>
    </div>

    @if (session('success'))
    <div class="bg-emerald-50 text-emerald-800 border border-emerald-200 px-5 py-4 rounded-xl flex items-center justify-between text-sm font-semibold shadow-sm">
        <div class="flex items-center gap-3">
            <span class="p-1 bg-emerald-600 text-white rounded-full">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            </span>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">&times;</button>
    </div>
    @endif

    {{-- Search & Filter Container --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
        <form action="{{ route('po.index') }}" method="GET" class="flex gap-3">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. PO, Nama Klien, Status..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm font-medium text-slate-800">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-sm transition">
                Cari
            </button>
            @if($search)
            <a href="{{ route('po.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-sm transition">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                        <th class="p-4 w-12 text-center">#</th>
                        <th class="p-4 min-w-[160px]">No. PO</th>
                        <th class="p-4 min-w-[180px]">Nama Klien</th>
                        <th class="p-4 min-w-[150px]">Ref Quotation</th>
                        <th class="p-4 min-w-[120px]">Tanggal PO</th>
                        <th class="p-4 min-w-[140px] text-right">Total Nilai</th>
                        <th class="p-4 w-28 text-center">Status</th>
                        <th class="p-4 w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($purchaseOrders as $idx => $po)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-4 text-center text-slate-400 font-bold">
                            {{ $purchaseOrders->firstItem() + $idx }}
                        </td>
                        <td class="p-4 font-mono font-bold text-blue-700">
                            {{ $po->po_number }}
                        </td>
                        <td class="p-4 text-slate-900 font-bold">
                            {{ $po->nama_klien }}
                        </td>
                        <td class="p-4 text-slate-600 text-xs">
                            @if($po->offer)
                                <a href="{{ route('histori.show', $po->offer->id) }}" class="text-blue-600 hover:underline font-bold">
                                    {{ $po->offer->no_surat ?: ('QUO #' . $po->offer->id) }}
                                </a>
                            @else
                                <span class="text-slate-400 italic">Manual (Tanpa Ref)</span>
                            @endif
                        </td>
                        <td class="p-4 text-slate-700">
                            {{ \Carbon\Carbon::parse($po->tanggal_po)->format('d/m/Y') }}
                        </td>
                        <td class="p-4 text-right font-extrabold text-slate-900">
                            Rp {{ number_format($po->total_nilai, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-center">
                            @php
                                $statusClasses = [
                                    'TERBIT' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                    'PROSES' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    'SELESAI' => 'bg-slate-100 text-slate-800 border-slate-300',
                                    'BATAL' => 'bg-red-100 text-red-800 border-red-300',
                                ];
                                $cls = $statusClasses[$po->status] ?? 'bg-slate-100 text-slate-800 border-slate-300';
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold border {{ $cls }}">
                                {{ $po->status }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('po.show', $po->id) }}" title="Lihat Detail PO"
                                    class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <form action="{{ route('po.destroy', $po->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus PO ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus PO" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm font-semibold">Belum ada data Purchase Order (PO).</p>
                                <a href="{{ route('po.create') }}" class="text-xs font-bold text-blue-600 hover:underline">
                                    + Tambah PO Pertama Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchaseOrders->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $purchaseOrders->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
