<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Invoice;
use App\Models\Product; // Anda meng-import ini, pastikan modelnya ada jika digunakan

class InvoiceController extends Controller
{
    /**
     * Menampilkan halaman histori dari semua invoice.
     */
    public function index(Request $request)
    {
        // Ambil kata kunci pencarian
        $search = $request->input('search');

        // Mulai query ke model Invoice
        $query = Invoice::query();

        // Jika ada pencarian, filter berdasarkan nama klien atau no. invoice
        if ($search) {
            $query->where('nama_klien', 'like', '%' . $search . '%')
                ->orWhere('no_invoice', 'like', '%' . $search . '%');
        }

        // Ambil data terbaru dengan pagination (15 per halaman)
        $invoices = $query->latest()->paginate(15);

        // Kirim data ke view
        return view('invoice.histori', [
            'invoices' => $invoices,
            'search' => $search ?? ''
        ]);
    }

    /**
     * Menampilkan form untuk membuat invoice baru (dari nol).
     */
    public function create()
    {
        return view('invoice.create');
    }

    /**
     * Menampilkan form invoice baru, dengan data yang ditarik dari Penawaran.
     */
    public function createFromOffer(Offer $offer)
    {
        $offer->load(['items', 'jasaItems']);
        return view('invoice.create_from_offer', [
            'offer' => $offer
        ]);
    }

    /**
     * Menyimpan invoice baru yang dibuat dari penawaran.
     */


    public function storeFromOffer(Request $request)
    {
        // Validasi data dasar
        $request->validate([
            'offer_id' => 'required|exists:offers,id',
            'po_file'  => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,pdf|max:10240',
        ]);

        $offer = Offer::find($request->offer_id);

        // Upload File PO dari client jika ada
        $poFilePath = null;
        if ($request->hasFile('po_file')) {
            $poFilePath = $request->file('po_file')->store('po_files', 'public');
        }

        // --- Kalkulasi Total di Backend ---
        $total_penawaran = $offer->total_keseluruhan;
        $total_tambahan = 0;
        $total_dp = 0;
        $diskon = $request->diskon ?? 0;

        if ($request->has('pekerjaan')) {
            foreach ($request->pekerjaan as $item) {
                $total_tambahan += $item['harga'] ?? 0;
            }
        }
        if ($request->has('dp')) {
            foreach ($request->dp as $item) {
                $total_dp += $item['jumlah'] ?? 0;
            }
        }

        $grand_total = ($total_penawaran + $total_tambahan) - $diskon;
        $sisa_pembayaran = $grand_total - $total_dp;
        // --- Akhir Kalkulasi ---

        // 1. Simpan data ke tabel 'invoices'
        $invoice = Invoice::create([
            'offer_id' => $offer->id,
            'no_invoice' => $request->no_invoice ?? 'INV-' . date('Ymd') . '-' . $offer->id,
            'nama_klien' => $offer->nama_klien,
            'po_file_path' => $poFilePath,
            'total_penawaran' => $total_penawaran,
            'total_tambahan' => $total_tambahan,
            'diskon' => $diskon,
            'grand_total' => $grand_total,
            'total_dp' => $total_dp,
            'sisa_pembayaran' => $sisa_pembayaran,
            'catatan_tambahan' => $request->catatan_tambahan,
            'tampilkan_comp_b' => $request->has('tampilkan_comp_b') ? 1 : 0,
        ]);

        // 2. Simpan data ke tabel 'invoice_additions'
        if ($request->has('pekerjaan')) {
            foreach ($request->pekerjaan as $itemData) {
                if (!empty($itemData['nama'])) {
                    $invoice->additions()->create([
                        'nama_pekerjaan' => $itemData['nama'],
                        'harga' => $itemData['harga'] ?? 0,
                    ]);
                }
            }
        }

        // 3. Simpan data ke tabel 'invoice_payments'
        if ($request->has('dp')) {
            foreach ($request->dp as $itemData) {
                if (!empty($itemData['keterangan'])) {
                    $invoice->payments()->create([
                        'keterangan' => $itemData['keterangan'],
                        'jumlah' => $itemData['jumlah'] ?? 0,
                    ]);
                }
            }
        }

        // 4. Update keterangan pada masing-masing produk penawaran
        if ($request->has('item_keterangan') && is_array($request->item_keterangan)) {
            foreach ($request->item_keterangan as $itemId => $ket) {
                \App\Models\OfferItem::where('id', $itemId)->update(['keterangan' => $ket]);
            }
        }

        // Alihkan ke halaman histori invoice dengan pesan sukses
        return redirect()->route('invoice.histori')->with('success', 'Invoice baru berhasil dibuat!');
    }

    /**
     * Menampilkan detail invoice.
     */
    public function show(Invoice $invoice)
    {
        // Load semua relasi yang dibutuhkan untuk 'show.blade.php'
        $invoice->load(['offer.items', 'offer.jasaItems', 'additions', 'payments']);

        return view('invoice.show', compact('invoice'));
    }

    /**
     * Menampilkan form untuk mengedit invoice.
     */
    public function edit(Invoice $invoice)
    {
        // Load relasi yang sama untuk form edit
        $invoice->load(['offer.items', 'offer.jasaItems', 'additions', 'payments']);

        // Mengarahkan ke view edit yang baru dibuat
        return view('invoice.edit', compact('invoice'));
    }


    /**
     * Memperbarui data invoice di database.
     */
    public function update(Request $request, Invoice $invoice)
    {
        // Validasi dasar (tambahkan sesuai kebutuhan)
        $request->validate([
            'diskon' => 'nullable|numeric|min:0',
            'po_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,pdf|max:10240',
            'pekerjaan.*.nama' => 'nullable|string',
            'pekerjaan.*.harga' => 'nullable|numeric|min:0',
            'dp.*.keterangan' => 'nullable|string',
            'dp.*.jumlah' => 'nullable|numeric|min:0',
        ]);

        // Upload/Update file PO jika ada
        $poFilePath = $invoice->po_file_path;
        if ($request->hasFile('po_file')) {
            if ($invoice->po_file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($invoice->po_file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($invoice->po_file_path);
            }
            $poFilePath = $request->file('po_file')->store('po_files', 'public');
        }

        // --- Kalkulasi Ulang Total di Backend ---
        $total_penawaran = $invoice->total_penawaran; // Ambil dari data yg ada
        $total_tambahan = 0;
        $total_dp = 0;
        $diskon = $request->diskon ?? 0;

        if ($request->has('pekerjaan')) {
            foreach ($request->pekerjaan as $item) {
                $total_tambahan += $item['harga'] ?? 0;
            }
        }
        if ($request->has('dp')) {
            foreach ($request->dp as $item) {
                $total_dp += $item['jumlah'] ?? 0;
            }
        }

        $grand_total = ($total_penawaran + $total_tambahan) - $diskon;
        $sisa_pembayaran = $grand_total - $total_dp;
        // --- Akhir Kalkulasi ---

        // 1. Update data di tabel 'invoices'
        $invoice->update([
            'no_invoice' => $request->no_invoice ?: $invoice->no_invoice,
            'po_file_path' => $poFilePath,
            'total_tambahan' => $total_tambahan,
            'diskon' => $diskon,
            'grand_total' => $grand_total,
            'total_dp' => $total_dp,
            'sisa_pembayaran' => $sisa_pembayaran,
            'catatan_tambahan' => $request->catatan_tambahan,
            'tampilkan_comp_b' => $request->has('tampilkan_comp_b') ? 1 : 0,
        ]);

        // 2. Hapus data lama dan simpan data baru ke 'invoice_additions'
        $invoice->additions()->delete();
        if ($request->has('pekerjaan')) {
            foreach ($request->pekerjaan as $itemData) {
                if (!empty($itemData['nama'])) {
                    $invoice->additions()->create([
                        'nama_pekerjaan' => $itemData['nama'],
                        'harga' => $itemData['harga'] ?? 0,
                    ]);
                }
            }
        }

        // 3. Hapus data lama dan simpan data baru ke 'invoice_payments'
        $invoice->payments()->delete();
        if ($request->has('dp')) {
            foreach ($request->dp as $itemData) {
                if (!empty($itemData['keterangan'])) {
                    $invoice->payments()->create([
                        'keterangan' => $itemData['keterangan'],
                        'jumlah' => $itemData['jumlah'] ?? 0,
                    ]);
                }
            }
        }

        // 4. Update keterangan pada masing-masing produk penawaran
        if ($request->has('item_keterangan') && is_array($request->item_keterangan)) {
            foreach ($request->item_keterangan as $itemId => $ket) {
                \App\Models\OfferItem::where('id', $itemId)->update(['keterangan' => $ket]);
            }
        }

        // Alihkan ke halaman show invoice dengan pesan sukses
        return redirect()->route('invoice.show', $invoice->id)->with('success', 'Invoice berhasil diperbarui!');
    }

    /**
     * Menghapus data invoice dari database.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoice.histori')->with('success', 'Invoice berhasil dihapus!');
    }

    public function print($id)
    {
        $invoice = Invoice::with(['offer.items', 'offer.jasaItems', 'additions', 'payments'])->findOrFail($id);
        return view('invoice.print', compact('invoice'));
    }

    public function printSuratJalan($id)
    {
        $invoice = Invoice::with(['offer.items', 'offer.jasaItems', 'additions'])->findOrFail($id);
        return view('invoice.print_surat_jalan', compact('invoice'));
    }

    public function printBoth($id)
    {
        $invoice = Invoice::with(['offer.items', 'offer.jasaItems', 'additions', 'payments'])->findOrFail($id);
        return view('invoice.print_both', compact('invoice'));
    }

    public function togglePaid(Request $request, Invoice $invoice)
    {
        $invoice->update([
            'is_paid' => $request->has('is_paid')
        ]);

        return redirect()->back()->with('success', 'Status lunas invoice berhasil diperbarui!');
    }

    public function addPayment(Request $request, $id)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'payment_receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $invoice = Invoice::with('paymentTransactions')->findOrFail($id);
        
        $path = null;
        if ($request->hasFile('payment_receipt')) {
            $path = $request->file('payment_receipt')->store('payments', 'public');
        }

        $invoice->paymentTransactions()->create([
            'amount' => $request->paid_amount,
            'payment_receipt' => $path,
        ]);

        $invoice->paid_amount += $request->paid_amount;
        
        // Sisa yang harus dibayar setelah potong DP
        $targetToPay = $invoice->grand_total - $invoice->total_dp;
        
        if ($invoice->paid_amount >= $targetToPay) {
            $invoice->is_paid = true;
        }

        $invoice->save();

        return redirect()->route('invoice.histori')->with('success', 'Pembayaran berhasil ditambahkan!');
    }
}
