@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center rounded-t-lg">
            <h2 class="text-xl font-bold text-gray-800">Pembuatan Statement of Account (SOA)</h2>
            <a href="{{ route('invoice.histori') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                &larr; Batal
            </a>
        </div>

        <div class="p-6">
            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-1">Klien:</p>
                <p class="text-lg font-bold text-gray-900">{{ $namaKlien }}</p>
            </div>

            <form action="{{ route('soa.store') }}" method="POST">
                @csrf
                <div class="overflow-x-auto rounded-lg border border-gray-200 mb-6">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs font-bold">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">No. Invoice</th>
                                <th class="px-4 py-3 text-right">Debit (Total)</th>
                                <th class="px-4 py-3 min-w-[200px]">Keterangan (Optional)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php $totalDebit = 0; @endphp
                            @foreach($invoices as $invoice)
                            @php $totalDebit += $invoice->grand_total; @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">{{ $invoice->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900 whitespace-nowrap">
                                    <input type="hidden" name="invoice_ids[]" value="{{ $invoice->id }}">
                                    {{ $invoice->no_invoice }}
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-green-600 whitespace-nowrap">
                                    {{ number_format($invoice->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="keterangan[{{ $invoice->id }}]" class="w-full text-xs rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-1.5 px-2" placeholder="Masukkan keterangan (opsional)...">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 font-bold border-t border-gray-200">
                                <td colspan="2" class="px-4 py-3 text-right text-gray-700">Total Keseluruhan:</td>
                                <td class="px-4 py-3 text-right text-blue-700">Rp {{ number_format($totalDebit, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('invoice.histori') }}" class="px-5 py-2.5 bg-gray-200 text-gray-800 font-bold rounded hover:bg-gray-300 transition shadow-sm">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded hover:bg-blue-700 transition shadow-sm">
                        Simpan SOA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
