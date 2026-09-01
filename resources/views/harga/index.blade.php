@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-7xl mx-auto">

        {{-- Header & Tombol Tambah --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Daftar Harga Produk
                </h1>
                <p class="text-sm text-gray-500">Kelola data produk, packing size, harga per liter, dan batch number.</p>
            </div>
            <a href="{{ route('harga.create') }}" class="bg-gray-800 text-white font-bold py-2.5 px-5 rounded-lg hover:bg-gray-700 transition ease-in-out duration-150 flex items-center gap-2 shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Produk Baru
            </a>
        </div>

        {{-- Alert Notification --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex justify-between items-center" role="alert">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove();" class="text-green-700 font-bold">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex justify-between items-center" role="alert">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove();" class="text-red-700 font-bold">&times;</button>
            </div>
        @endif

        {{-- Search & Filter Section --}}
        <div class="bg-white p-5 rounded-xl shadow-md mb-6 border border-gray-100">
            <form method="GET" action="{{ route('harga.index') }}" class="space-y-4">
                {{-- Search Bar --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari kata kunci (nama produk, generic, category, thinner, batch number)..."
                           class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800 text-sm">
                </div>

                {{-- Filter Bar Dropdowns --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    {{-- Filter Generic --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Filter Generic</label>
                        <select name="generic" class="w-full rounded-lg border-gray-300 text-sm focus:border-gray-800 focus:ring-gray-800">
                            <option value="">-- Semua Generic --</option>
                            @foreach ($genericsList as $gen)
                                <option value="{{ $gen }}" {{ request('generic') == $gen ? 'selected' : '' }}>{{ $gen }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Primer / Topcoat --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Filter Primer/Topcoat</label>
                        <select name="primer_topcoat" class="w-full rounded-lg border-gray-300 text-sm focus:border-gray-800 focus:ring-gray-800">
                            <option value="">-- Semua Primer/Topcoat --</option>
                            <option value="Primer" {{ request('primer_topcoat') == 'Primer' ? 'selected' : '' }}>Primer</option>
                            <option value="Topcoat" {{ request('primer_topcoat') == 'Topcoat' ? 'selected' : '' }}>Topcoat</option>
                        </select>
                    </div>

                    {{-- Filter Category --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Filter Category</label>
                        <select name="category" class="w-full rounded-lg border-gray-300 text-sm focus:border-gray-800 focus:ring-gray-800">
                            <option value="">-- Semua Category --</option>
                            <option value="Marine" {{ request('category') == 'Marine' ? 'selected' : '' }}>Marine</option>
                            <option value="Marine & PC" {{ request('category') == 'Marine & PC' ? 'selected' : '' }}>Marine & PC</option>
                            <option value="PC - Floor Coating" {{ request('category') == 'PC - Floor Coating' ? 'selected' : '' }}>PC - Floor Coating</option>
                        </select>
                    </div>

                    {{-- Filter Thinner --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Filter Thinner</label>
                        <select name="thinner" class="w-full rounded-lg border-gray-300 text-sm focus:border-gray-800 focus:ring-gray-800">
                            <option value="">-- Semua Thinner --</option>
                            @foreach ($thinnersList as $th)
                                <option value="{{ $th }}" {{ request('thinner') == $th ? 'selected' : '' }}>{{ $th }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-2 pt-1 border-t border-gray-100">
                    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition flex items-center gap-1 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Terapkan Filter
                    </button>
                    @if (request('search') || request('generic') || request('primer_topcoat') || request('category') || request('thinner'))
                        <a href="{{ route('harga.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300 transition flex items-center">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table Daftar Produk --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-100 overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 min-w-[950px]">
                <thead class="text-xs text-white uppercase bg-gray-800">
                    <tr>
                        <th scope="col" class="px-5 py-3.5">
                            Nama Produk
                        </th>
                        <th scope="col" class="px-4 py-3.5">
                            Generic
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-center">
                            Primer/Topcoat
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-center">
                            Category
                        </th>
                        <th scope="col" class="px-4 py-3.5">
                            Thinner
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-center">
                            Packing Size (L)
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-right">
                            Price per (L)
                        </th>
                        <th scope="col" class="px-4 py-3.5">
                            Batch Number
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-center">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($products as $product)
                    <tr class="bg-white hover:bg-gray-50 transition-colors">
                        {{-- Nama Produk --}}
                        <th scope="row" class="px-5 py-4 font-semibold text-gray-900 whitespace-nowrap">
                            <div>{{ $product->nama_produk }}</div>
                            @if($product->comp_b)
                                <div class="text-[11px] font-normal text-purple-700 bg-purple-50 inline-block px-1.5 py-0.5 rounded border border-purple-200 mt-1">
                                    Comp B: {{ $product->comp_b }}
                                    @if($product->packing_size_b)
                                        <span class="font-semibold text-purple-900">({{ $product->packing_size_b }})</span>
                                    @endif
                                </div>
                            @endif
                        </th>

                        {{-- Generic --}}
                        <td class="px-4 py-4 text-xs text-gray-600">
                            {{ $product->generic ?? '-' }}
                        </td>

                        {{-- Primer / Topcoat --}}
                        <td class="px-4 py-4 text-center whitespace-nowrap">
                            @if ($product->primer_topcoat)
                                <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-2 py-0.5 rounded border border-indigo-200">
                                    {{ $product->primer_topcoat }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>

                        {{-- Category --}}
                        <td class="px-4 py-4 text-center whitespace-nowrap">
                            @if ($product->category)
                                <span class="bg-emerald-50 text-emerald-700 text-xs font-semibold px-2 py-0.5 rounded border border-emerald-200">
                                    {{ $product->category }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>

                        {{-- Thinner --}}
                        <td class="px-4 py-4 text-xs text-gray-600">
                            {{ $product->thinner ?? '-' }}
                        </td>

                        {{-- Packing Size (L) (Multiple) --}}
                        <td class="px-4 py-4 text-center">
                            @if ($product->packings->count() > 0)
                                <div class="flex flex-wrap justify-center gap-1">
                                    @foreach ($product->packings as $pack)
                                        <span class="inline-flex items-center bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-0.5 rounded border border-gray-300">
                                            {{ $pack->packing_size }}
                                        </span>
                                    @endforeach
                                </div>
                            @elseif ($product->packing_size)
                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-0.5 rounded border border-gray-300">
                                    {{ $product->packing_size }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs italic">-</span>
                            @endif
                            @if($product->comp_b && $product->packing_size_b)
                                <div class="text-[10px] text-purple-700 font-semibold mt-1">
                                    Comp B: {{ $product->packing_size_b }}
                                </div>
                            @endif
                        </td>

                        {{-- Price per (L) --}}
                        <td class="px-4 py-4 text-right font-bold whitespace-nowrap text-gray-900">
                            Rp {{ number_format($product->price_per_l ?? $product->harga ?? 0, 0, ',', '.') }}
                        </td>

                        {{-- Batch Number (Optional, Multiple) --}}
                        <td class="px-4 py-4">
                            @if ($product->batches->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($product->batches as $batch)
                                        <span class="inline-flex items-center bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-blue-200">
                                            #{{ $batch->batch_number }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 text-xs italic">Tidak ada batch</span>
                            @endif
                        </td>

                        {{-- Action --}}
                        <td class="px-4 py-4 text-center whitespace-nowrap">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('harga.edit', $product->id) }}" class="font-medium text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1 text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>

                                <form action="{{ route('harga.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:text-red-800 transition-colors flex items-center gap-1 text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-10 text-center text-gray-500 italic">
                            @if (request('search') || request('generic') || request('primer_topcoat') || request('category') || request('thinner'))
                                Tidak ditemukan produk dengan kriteria filter/pencarian yang dipilih.
                            @else
                                Belum ada data produk yang tersedia.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection