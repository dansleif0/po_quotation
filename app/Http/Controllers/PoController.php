<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Offer;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;

class PoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $purchaseOrders = PurchaseOrder::with(['offer', 'client', 'items'])
            ->when($search, function ($query, $search) {
                $query->where('po_number', 'like', '%' . $search . '%')
                    ->orWhere('nama_klien', 'like', '%' . $search . '%')
                    ->orWhere('supplier_name', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('po.index', compact('purchaseOrders', 'search'));
    }

    public function create(Request $request)
    {
        $offerId = $request->get('offer_id');
        $selectedOffer = $offerId ? Offer::with('items')->find($offerId) : null;

        $clients  = Client::orderBy('nama_klien', 'asc')->get();
        $offers   = Offer::with('items')->latest()->get();
        $products = Product::orderBy('nama_produk', 'asc')->get();

        // Format Auto PO Number Format Kantor: POTGI01092064
        $count = PurchaseOrder::count() + 1;
        $poNumber = 'POTGI' . date('m') . date('Y') . str_pad($count, 2, '0', STR_PAD_LEFT);

        return view('po.create', compact('clients', 'offers', 'products', 'selectedOffer', 'poNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_number'    => 'required|string',
            'nama_klien'   => 'required|string',
            'tanggal_po'   => 'required|date',
            'total_nilai'  => 'required|numeric',
        ]);

        $offer = $request->offer_id ? Offer::find($request->offer_id) : null;
        $jobProjectDefault = $offer ? ($offer->project_no ?: $offer->no_surat) : $request->offer_letter;

        $po = PurchaseOrder::create([
            'po_number'          => $request->po_number,
            'offer_id'           => $request->offer_id ?: null,
            'client_id'          => $request->client_id ?: null,
            'nama_klien'         => $request->nama_klien,
            'client_details'     => $request->client_details,
            'supplier_name'      => $request->supplier_name ?: 'PT CIPTA MARITIM PERKASA',
            'supplier_address'   => $request->supplier_address ?: 'Ruko Tunas Regency Blok A5 No 09 – 10 Tanjung Uncang',
            'deliver_to_name'    => $request->deliver_to_name ?: 'PT TASNIEM GERAI INSPIRASI',
            'deliver_to_address' => $request->deliver_to_address ?: 'Komp. Ruko KDA Junction Blok C 8-9',
            'currency'           => $request->currency ?: 'IDR',
            'delivery_date'      => $request->delivery_date ?: '-',
            'offer_letter'       => $request->offer_letter,
            'payment_term'       => $request->payment_term ?: 'BANK TRANSFER',
            'job_project'        => $request->job_project ?: $jobProjectDefault,
            'issued_by'          => $request->issued_by ?: 'Ardian Wijaya Kusuma',
            'approved_by'        => $request->approved_by ?: 'Samsu Rizal',
            'tanggal_po'         => $request->tanggal_po,
            'total_nilai'        => $request->total_nilai,
            'status'             => $request->status ?: 'TERBIT',
            'catatan'            => $request->catatan,
        ]);

        // Save Items
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $item) {
                if (!empty($item['nama_produk'])) {
                    $qty = floatval($item['qty_order'] ?? 1);
                    $consumption = floatval($item['consumption_l'] ?? 1);
                    $price = floatval($item['price_per_liter'] ?? 0);
                    $totalPrice = floatval($item['total_price'] ?? ($consumption * $price));

                    PurchaseOrderItem::create([
                        'purchase_order_id' => $po->id,
                        'nama_produk'       => $item['nama_produk'],
                        'packing_size'      => $item['packing_size'] ?? '5 L',
                        'qty_order'         => $qty,
                        'consumption_l'     => $consumption,
                        'price_per_liter'   => $price,
                        'total_price'       => $totalPrice,
                    ]);
                }
            }
        }

        return redirect()->route('po.index')->with('success', "Purchase Order {$po->po_number} berhasil disimpan!");
    }

    public function show($id)
    {
        $po = PurchaseOrder::with(['offer', 'client', 'items'])->findOrFail($id);
        return view('po.show', compact('po'));
    }

    public function edit($id)
    {
        $po = PurchaseOrder::with(['offer', 'client', 'items'])->findOrFail($id);
        $clients  = Client::orderBy('nama_klien', 'asc')->get();
        $offers   = Offer::with('items')->latest()->get();
        $products = Product::orderBy('nama_produk', 'asc')->get();

        return view('po.edit', compact('po', 'clients', 'offers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);

        $request->validate([
            'po_number'    => 'required|string',
            'nama_klien'   => 'required|string',
            'tanggal_po'   => 'required|date',
            'total_nilai'  => 'required|numeric',
        ]);

        $offer = $request->offer_id ? Offer::find($request->offer_id) : null;
        $jobProjectDefault = $offer ? ($offer->project_no ?: $offer->no_surat) : $request->offer_letter;

        $po->update([
            'po_number'          => $request->po_number,
            'offer_id'           => $request->offer_id ?: null,
            'client_id'          => $request->client_id ?: null,
            'nama_klien'         => $request->nama_klien,
            'client_details'     => $request->client_details,
            'supplier_name'      => $request->supplier_name ?: 'PT CIPTA MARITIM PERKASA',
            'supplier_address'   => $request->supplier_address ?: 'Ruko Tunas Regency Blok A5 No 09 – 10 Tanjung Uncang',
            'deliver_to_name'    => $request->deliver_to_name ?: 'PT TASNIEM GERAI INSPIRASI',
            'deliver_to_address' => $request->deliver_to_address ?: 'Komp. Ruko KDA Junction Blok C 8-9',
            'currency'           => $request->currency ?: 'IDR',
            'delivery_date'      => $request->delivery_date ?: '-',
            'offer_letter'       => $request->offer_letter,
            'payment_term'       => $request->payment_term ?: 'BANK TRANSFER',
            'job_project'        => $request->job_project ?: $jobProjectDefault,
            'issued_by'          => $request->issued_by ?: 'Ardian Wijaya Kusuma',
            'approved_by'        => $request->approved_by ?: 'Samsu Rizal',
            'tanggal_po'         => $request->tanggal_po,
            'total_nilai'        => $request->total_nilai,
            'status'             => $request->status ?: 'TERBIT',
            'catatan'            => $request->catatan,
        ]);

        // Replace items
        $po->items()->delete();

        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $item) {
                if (!empty($item['nama_produk'])) {
                    $qty = floatval($item['qty_order'] ?? 1);
                    $consumption = floatval($item['consumption_l'] ?? 1);
                    $price = floatval($item['price_per_liter'] ?? 0);
                    $totalPrice = floatval($item['total_price'] ?? ($consumption * $price));

                    PurchaseOrderItem::create([
                        'purchase_order_id' => $po->id,
                        'nama_produk'       => $item['nama_produk'],
                        'packing_size'      => $item['packing_size'] ?? '5 L',
                        'qty_order'         => $qty,
                        'consumption_l'     => $consumption,
                        'price_per_liter'   => $price,
                        'total_price'       => $totalPrice,
                    ]);
                }
            }
        }

        return redirect()->route('po.index')->with('success', "Purchase Order {$po->po_number} berhasil diperbarui!");
    }

    public function print($id)
    {
        $po = PurchaseOrder::with(['offer', 'client', 'items'])->findOrFail($id);
        return view('po.print', compact('po'));
    }

    public function destroy($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        $po->delete();

        return redirect()->route('po.index')->with('success', 'Purchase Order berhasil dihapus!');
    }
}
