<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Offer;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Offer::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_klien', 'like', '%' . $search . '%')
                    ->orWhere('no_surat', 'like', '%' . $search . '%')
                    ->orWhere('project_no', 'like', '%' . $search . '%')
                    ->orWhere('id', $search);
            });
        }

        $offers = $query->latest()->paginate(15);
        return view('histori.index', compact('offers', 'search'));
    }

    public function create()
    {
        $clients = Client::orderBy('nama_klien', 'asc')->get();
        $products = Product::orderBy('nama_produk', 'asc')->get();

        // Format No. Surat Resmi Kantor: 0010133/SP/TGI-1/VIII/2026
        $bulanRomawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $romawi = $bulanRomawi[(int)date('n')];
        $tahun  = date('Y');

        $lastOffer = Offer::latest('id')->first();
        $nextId = ($lastOffer ? $lastOffer->id : 0) + 1;
        $seq = str_pad(10132 + $nextId, 7, '0', STR_PAD_LEFT);

        $noSurat = "{$seq}/SP/TGI-1/{$romawi}/{$tahun}";

        return view('penawaran.create', compact('clients', 'products', 'noSurat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_surat'              => 'nullable|string|max:255',
            'project_no'            => 'nullable|string|max:255',
            'nama_klien'            => 'required|string|max:255',
            'client_details'        => 'nullable|string',
            'perihal'               => 'nullable|string|max:255',
            'items'                 => 'required|array|min:1',
            'items.*.nama_produk'   => 'required|string|max:255',
            'items.*.qty_order'     => 'required|numeric|min:0',
            'items.*.consumption_l' => 'required|numeric|min:0',
            'items.*.price_per_liter' => 'required|numeric|min:0',
            'diskon_global'         => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $grandTotal = 0;

            if ($request->has('items')) {
                foreach ($request->items as $itemData) {
                    $consumption = (float)($itemData['consumption_l'] ?? 0);
                    $price = (float)($itemData['price_per_liter'] ?? 0);
                    $grandTotal += ($consumption * $price);
                }
            }

            $diskonGlobal = (float)($request->diskon_global ?? 0);
            $finalTotal = max(0, $grandTotal - $diskonGlobal);

            $offer = Offer::create([
                'no_surat'            => $request->no_surat,
                'project_no'          => $request->project_no,
                'client_id'           => $request->client_id,
                'nama_klien'          => $request->nama_klien,
                'client_details'      => $request->client_details,
                'perihal'             => $request->perihal ?? 'Penawaran Quotation Produk',
                'jenis_penawaran'     => 'produk',
                'diskon_global'       => $diskonGlobal,
                'total_keseluruhan'   => $finalTotal,
            ]);

            if ($request->has('items')) {
                foreach ($request->items as $itemData) {
                    $status = $itemData['status_produk'] ?? 'READY';
                    if ($status === 'OTHER' && !empty($itemData['status_other'])) {
                        $status = $itemData['status_other'];
                    }

                    $pricePerLiter = (float)($itemData['price_per_liter'] ?? 0);
                    $basePrice = (float)($itemData['base_price_per_liter'] ?? 0);
                    $consumption = (float)($itemData['consumption_l'] ?? 0);
                    $qtyOrder = (float)($itemData['qty_order'] ?? 0);

                    $offer->items()->create([
                        'product_id'           => $itemData['product_id'] ?? null,
                        'nama_produk'          => $itemData['nama_produk'],
                        'packing_size'         => $itemData['packing_size'] ?? '',
                        'qty_order'            => $qtyOrder,
                        'consumption_l'        => $consumption,
                        'status_produk'        => $status,
                        'price_per_liter'      => $pricePerLiter,
                        'base_price_per_liter' => $basePrice,
                        'harga_per_m2'         => $pricePerLiter,
                        'volume'               => $consumption,
                    ]);
                }
            }

            return redirect()->route('histori.index')->with('success', 'Quotation berhasil dibuat!');
        });
    }

    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'nama_klien' => 'required|string|max:255',
            'client_details' => 'nullable|string',
            'perihal' => 'nullable|string|max:255',
            'produk.*.nama' => 'nullable|string',
            'jasa.*.nama' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $offer) {
            $totalProduk = 0;
            $totalJasa = 0;

            // Hitung Total
            if ($request->has('produk')) {
                foreach ($request->produk as $p) {
                    if (!empty($p['nama'])) {
                        $totalProduk += ((float)($p['volume'] ?? 0) * (float)($p['harga'] ?? 0));
                    }
                }
            }

            if ($request->has('jasa')) {
                foreach ($request->jasa as $j) {
                    if (!empty($j['nama'])) {
                        $totalJasa += ((float)($j['volume'] ?? 0) * (float)($j['harga'] ?? 0));
                    }
                }
            }

            $data = [
                'nama_klien'            => $request->nama_klien,
                'client_details'        => $request->client_details,
                'perihal'               => $request->perihal ?? 'Penawaran Jasa Apply dan Supply Pengecatan',
                'total_keseluruhan'     => $totalProduk + $totalJasa,
                'pisah_kriteria_total'  => $request->has('pisah_kriteria_total') ? 1 : 0,
                'hilangkan_grand_total' => $request->has('hilangkan_grand_total') ? 1 : 0,
                'opsi_paket'            => $request->has('opsi_paket') ? 1 : 0,
                'jenis_penawaran'       => $offer->jenis_penawaran ?? 'jasa',
            ];

            if ($request->input('action') == 'save_and_copy') {
                if ($data['nama_klien'] === $offer->nama_klien) {
                    $data['nama_klien'] .= ' (Copy)';
                }
                $targetOffer = Offer::create($data);
                $message = 'Data berhasil disalin sebagai Penawaran Baru!';
            } else {
                $offer->update($data);
                $targetOffer = $offer;
                $targetOffer->items()->delete();
                $targetOffer->jasaItems()->delete();
                $message = 'Surat penawaran berhasil diperbarui.';
            }

            // Simpan Item Produk ke $targetOffer
            if ($request->has('produk')) {
                foreach ($request->produk as $pData) {
                    if (!empty($pData['nama'])) {
                        $targetOffer->items()->create([
                            'nama_produk'  => $pData['nama'],
                            'area_dinding' => $pData['area'] ?? '',
                            'volume'       => (float)($pData['volume'] ?? 0),
                            'harga_per_m2' => (float)($pData['harga'] ?? 0),
                        ]);
                    }
                }
            }

            // Simpan Item Jasa ke $targetOffer
            if ($request->has('jasa')) {
                foreach ($request->jasa as $jData) {
                    if (!empty($jData['nama'])) {
                        $vJ = (float) ($jData['volume'] ?? 0);
                        $hJ = (float) ($jData['harga'] ?? 0);
                        $targetOffer->jasaItems()->create([
                            'nama_jasa'    => $jData['nama'],
                            'volume'       => $vJ,
                            'satuan'       => $jData['satuan'] ?? 'Ls',
                            'harga_satuan' => $hJ,
                            'harga_jasa'   => $vJ * $hJ,
                        ]);
                    }
                }
            }

            if ($request->input('action') == 'save_and_copy') {
                return redirect()->route('histori.edit', $targetOffer->id)->with('success', $message);
            }
            return redirect()->route('histori.index')->with('success', $message);
        });
    }

    // ... method lainnya (show, edit, destroy, print) tetap sama ...
    public function show($id)
    {
        $offer = Offer::with(['items', 'jasaItems'])->findOrFail($id);
        $view = ($offer->jenis_penawaran == 'produk') ? 'histori.show_product' : 'histori.show';
        return view($view, compact('offer'));
    }

    public function edit(Offer $offer)
    {
        $offer->load(['items', 'jasaItems']);
        $all_products = Product::all();
        return view('histori.edit', compact('offer', 'all_products'));
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('histori.index')->with('success', 'Data penawaran berhasil dihapus!');
    }

    public function print($id)
    {
        $offer = Offer::with(['items', 'jasaItems'])->findOrFail($id);
        return view('histori.print', compact('offer'));
    }
}