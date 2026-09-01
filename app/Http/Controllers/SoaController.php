<?php

namespace App\Http\Controllers;

use App\Models\Soa;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SoaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $soas = Soa::with('invoices')
            ->when($search, function ($query, $search) {
                return $query->where('no_soa', 'like', "%{$search}%")
                             ->orWhere('nama_klien', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('soa.histori', compact('soas', 'search'));
    }

    public function create(Request $request)
    {
        $invoiceIds = $request->invoice_ids;
        if (!$invoiceIds || count($invoiceIds) < 2) {
            return redirect()->back()->with('error', 'Pilih minimal 2 invoice untuk membuat SOA.');
        }

        $invoices = Invoice::whereIn('id', $invoiceIds)->orderBy('created_at', 'asc')->get();
        if ($invoices->isEmpty()) {
            return redirect()->back()->with('error', 'Invoice tidak ditemukan.');
        }

        // Asumsi semua invoice memiliki nama klien yang sama
        $namaKlien = $invoices->first()->nama_klien;

        return view('soa.create', compact('invoices', 'namaKlien', 'invoiceIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_ids' => 'required|array|min:2',
            'keterangan' => 'array',
        ]);

        $invoices = Invoice::whereIn('id', $request->invoice_ids)->orderBy('created_at', 'asc')->get();
        if ($invoices->isEmpty()) {
            return redirect()->back()->with('error', 'Invoice tidak valid.');
        }

        // Generate No SOA
        $bulanRomawi = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        $bulan = $bulanRomawi[date('n')];
        $tahun = date('Y');
        
        // Cari urutan terakhir SOA di bulan dan tahun ini
        $lastSoa = Soa::whereYear('created_at', $tahun)->whereMonth('created_at', date('n'))->latest()->first();
        $urut = 1;
        if ($lastSoa) {
            $parts = explode('/', $lastSoa->no_soa);
            if (isset($parts[0])) {
                $urut = (int)$parts[0] + 1;
            }
        }
        $noSoa = sprintf("%03d/SOA/TGI/%s/%s", $urut, $bulan, $tahun);

        $soa = Soa::create([
            'no_soa' => $noSoa,
            'nama_klien' => $invoices->first()->nama_klien,
            'tanggal_soa' => now()->toDateString(),
        ]);

        // Attach invoices with keterangan
        $attachData = [];
        foreach ($invoices as $invoice) {
            $keterangan = $request->keterangan[$invoice->id] ?? null;
            $attachData[$invoice->id] = ['keterangan' => $keterangan];
        }
        $soa->invoices()->attach($attachData);

        return redirect()->route('soa.histori')->with('success', 'SOA berhasil dibuat!');
    }

    public function print($id)
    {
        $soa = Soa::with(['invoices' => function($q) {
            $q->orderBy('created_at', 'asc');
        }])->findOrFail($id);
        return view('soa.print', compact('soa'));
    }

    public function destroy($id)
    {
        $soa = Soa::findOrFail($id);
        $soa->delete();
        return redirect()->route('soa.histori')->with('success', 'SOA berhasil dihapus!');
    }

    public function addPayment(Request $request, $id)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'payment_receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $soa = Soa::with(['invoices', 'paymentTransactions'])->findOrFail($id);
        
        $totalTagihan = $soa->invoices->sum('grand_total');
        
        $path = null;
        if ($request->hasFile('payment_receipt')) {
            $path = $request->file('payment_receipt')->store('payments', 'public');
        }

        $soa->paymentTransactions()->create([
            'amount' => $request->paid_amount,
            'payment_receipt' => $path,
        ]);

        $soa->paid_amount += $request->paid_amount;
        
        // Cek apakah sudah lunas
        if ($soa->paid_amount >= $totalTagihan) {
            $soa->is_paid = true;
            // Sinkronisasi: Tandai semua invoice terkait menjadi lunas
            foreach ($soa->invoices as $invoice) {
                $invoice->is_paid = true;
                $invoice->save();
            }
        } else {
            $soa->is_paid = false;
        }

        $soa->save();

        return redirect()->route('soa.histori')->with('success', 'Pembayaran berhasil ditambahkan!');
    }
}
