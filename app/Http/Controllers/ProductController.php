<?php

namespace App\Http\Controllers;

use App\Models\Product; // Import Model Product
use App\Models\Offer;   // Import Model Offer
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk DB Transaction

class ProductController extends Controller
{
    // =========================================================================
    // 1. MASTER DATA PRODUK (DAFTAR HARGA)
    // =========================================================================

    // =========================================================================
    // 1. MASTER DATA PRODUK (DAFTAR HARGA)
    // =========================================================================

    // Menampilkan daftar harga (Read) dengan pencarian & filter (Generic, Primer/Topcoat, Category, Thinner)
    public function index(Request $request)
    {
        $search              = trim($request->input('search'));
        $filterGeneric       = trim($request->input('generic'));
        $filterPrimerTopcoat = trim($request->input('primer_topcoat'));
        $filterCategory      = trim($request->input('category'));
        $filterThinner       = trim($request->input('thinner'));

        $query = Product::with(['batches', 'packings']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'LIKE', "%{$search}%")
                  ->orWhere('generic', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%")
                  ->orWhere('primer_topcoat', 'LIKE', "%{$search}%")
                  ->orWhere('thinner', 'LIKE', "%{$search}%")
                  ->orWhereHas('batches', function ($bq) use ($search) {
                      $bq->where('batch_number', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('packings', function ($pq) use ($search) {
                      $pq->where('packing_size', 'LIKE', "%{$search}%");
                  });
            });
        }

        if (!empty($filterGeneric)) {
            $query->where('generic', $filterGeneric);
        }

        if (!empty($filterPrimerTopcoat)) {
            $query->where('primer_topcoat', $filterPrimerTopcoat);
        }

        if (!empty($filterCategory)) {
            $query->where('category', $filterCategory);
        }

        if (!empty($filterThinner)) {
            $query->where('thinner', $filterThinner);
        }

        $products = $query->latest()->get();

        // Opsi filter dari database
        $genericsList = Product::whereNotNull('generic')->where('generic', '!=', '')->distinct()->orderBy('generic')->pluck('generic');
        $thinnersList = Product::whereNotNull('thinner')->where('thinner', '!=', '')->distinct()->orderBy('thinner')->pluck('thinner');

        return view('harga.index', compact(
            'products',
            'search',
            'filterGeneric',
            'filterPrimerTopcoat',
            'filterCategory',
            'filterThinner',
            'genericsList',
            'thinnersList'
        ));
    }

    // Form tambah harga baru (Create View)
    public function create()
    {
        return view('harga.create');
    }

    // Menyimpan data harga baru (Create Action)
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_produk'    => 'required|string|max:255',
            'comp_b'         => 'nullable|string|max:255',
            'generic'        => 'nullable|string|max:255',
            'primer_topcoat' => 'nullable|string|in:Primer,Topcoat',
            'category'       => 'nullable|string|in:Marine,Marine & PC,PC - Floor Coating',
            'thinner'        => 'nullable|string|max:255',
            'price_per_l'    => 'required|numeric|min:0',
            'packing_sizes'  => 'nullable|array',
            'packing_sizes.*'=> 'nullable|string|max:255',
            'batch_numbers'  => 'nullable|array',
            'batch_numbers.*'=> 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Ambil packing size pertama jika ada untuk kolom legacy
            $firstPacking = null;
            if ($request->has('packing_sizes') && is_array($request->packing_sizes)) {
                foreach ($request->packing_sizes as $p) {
                    if (!empty(trim($p))) {
                        $firstPacking = trim($p);
                        break;
                    }
                }
            }

            // 2. Simpan Produk
            $product = Product::create([
                'nama_produk'    => $request->nama_produk,
                'comp_b'         => $request->comp_b,
                'generic'        => $request->generic,
                'primer_topcoat' => $request->primer_topcoat,
                'category'       => $request->category,
                'thinner'        => $request->thinner,
                'packing_size'   => $firstPacking,
                'price_per_l'    => $request->price_per_l,
                'harga'          => $request->price_per_l, // Untuk backward compatibility
            ]);

            // 3. Simpan Packing Sizes
            if ($request->has('packing_sizes') && is_array($request->packing_sizes)) {
                foreach ($request->packing_sizes as $packSize) {
                    $trimmed = trim($packSize);
                    if (!empty($trimmed)) {
                        $product->packings()->create([
                            'packing_size' => $trimmed,
                        ]);
                    }
                }
            }

            // 4. Simpan Batch Numbers
            if ($request->has('batch_numbers') && is_array($request->batch_numbers)) {
                foreach ($request->batch_numbers as $batchNum) {
                    $trimmed = trim($batchNum);
                    if (!empty($trimmed)) {
                        $product->batches()->create([
                            'batch_number' => $trimmed,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('harga.index')->with('success', 'Data produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    // Form edit harga (Edit View)
    public function edit(Product $product)
    {
        $product->load(['batches', 'packings']);
        return view('harga.edit', compact('product'));
    }

    // Update data harga (Update Action)
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk'    => 'required|string|max:255',
            'comp_b'         => 'nullable|string|max:255',
            'generic'        => 'nullable|string|max:255',
            'primer_topcoat' => 'nullable|string|in:Primer,Topcoat',
            'category'       => 'nullable|string|in:Marine,Marine & PC,PC - Floor Coating',
            'thinner'        => 'nullable|string|max:255',
            'price_per_l'    => 'required|numeric|min:0',
            'packing_sizes'  => 'nullable|array',
            'packing_sizes.*'=> 'nullable|string|max:255',
            'batch_numbers'  => 'nullable|array',
            'batch_numbers.*'=> 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $firstPacking = null;
            if ($request->has('packing_sizes') && is_array($request->packing_sizes)) {
                foreach ($request->packing_sizes as $p) {
                    if (!empty(trim($p))) {
                        $firstPacking = trim($p);
                        break;
                    }
                }
            }

            $product->update([
                'nama_produk'    => $request->nama_produk,
                'comp_b'         => $request->comp_b,
                'generic'        => $request->generic,
                'primer_topcoat' => $request->primer_topcoat,
                'category'       => $request->category,
                'thinner'        => $request->thinner,
                'packing_size'   => $firstPacking,
                'price_per_l'    => $request->price_per_l,
                'harga'          => $request->price_per_l, // Untuk backward compatibility
            ]);

            // Sync Packing Sizes
            $product->packings()->delete();
            if ($request->has('packing_sizes') && is_array($request->packing_sizes)) {
                foreach ($request->packing_sizes as $packSize) {
                    $trimmed = trim($packSize);
                    if (!empty($trimmed)) {
                        $product->packings()->create([
                            'packing_size' => $trimmed,
                        ]);
                    }
                }
            }

            // Sync Batch Numbers
            $product->batches()->delete();
            if ($request->has('batch_numbers') && is_array($request->batch_numbers)) {
                foreach ($request->batch_numbers as $batchNum) {
                    $trimmed = trim($batchNum);
                    if (!empty($trimmed)) {
                        $product->batches()->create([
                            'batch_number' => $trimmed,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('harga.index')->with('success', 'Data produk berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    // Menghapus data harga (Delete Action)
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('harga.index')->with('success', 'Data produk berhasil dihapus!');
    }

    // =========================================================================
    // 2. LOGIKA PENAWARAN PROYEK (COMBINED)
    // =========================================================================
    // Catatan: Logika ini khusus untuk membuat Offer Tipe Proyek
    // yang menggabungkan Produk & Jasa secara manual.

    public function createCombined(Request $request)
    {
        $token = Str::uuid()->toString();
        $request->session()->put('form_token', $token);
        $products = Product::all();

        // Pastikan view ini ada
        return view('penawaran.create_combined', ['products' => $products]);
    }

    public function storeCombined(Request $request)
    {
        $request->validate([
            'nama_klien' => 'required|string|max:255',
            'perihal'    => 'nullable|string|max:255',
        ]);

        // Hitung total di backend
        $totalProduk = 0;
        if ($request->has('produk')) {
            foreach ($request->produk as $item) {
                $totalProduk += ($item['volume'] ?? 0) * ($item['harga'] ?? 0);
            }
        }

        $totalJasa = 0;
        if ($request->has('jasa')) {
            foreach ($request->jasa as $item) {
                $totalJasa += ((float)($item['volume'] ?? 0) * (float)($item['harga'] ?? 0));
            }
        }

        DB::beginTransaction(); // Gunakan transaksi database agar aman
        try {
            // 1. Simpan Offer Utama
            $offer = Offer::create([
                'nama_klien'            => $request->nama_klien,
                'client_details'        => $request->client_details,
                'perihal'               => $request->perihal ?? 'Penawaran Jasa Apply dan Supply Pengecatan',
                'total_keseluruhan'     => $totalProduk + $totalJasa,
                'tampilkan_comp_b'      => $request->has('tampilkan_comp_b') ? 1 : 0,
                'hilangkan_grand_total' => $request->has('hilangkan_grand_total') ? 1 : 0,
                'jenis_penawaran'       => 'proyek', // Tambahkan penanda jenis jika perlu
            ]);

            // 2. Simpan Item Produk
            if ($request->has('produk')) {
                foreach ($request->produk as $itemData) {
                    if (!empty($itemData['nama'])) {
                        $prod = Product::where('nama_produk', $itemData['nama'])->first();
                        $offer->items()->create([
                            'product_id'   => $prod?->id,
                            'nama_produk'  => $itemData['nama'],
                            'comp_b'       => $prod?->comp_b,
                            'area_dinding' => $itemData['area'] ?? '',
                            'volume'       => $itemData['volume'] ?? 0,
                            'harga_per_m2' => $itemData['harga'] ?? 0,
                        ]);
                    }
                }
            }

            // 3. Simpan Item Jasa
            if ($request->has('jasa')) {
                foreach ($request->jasa as $jasaData) {
                    if (!empty($jasaData['nama'])) {
                        $vJ = (float) ($jasaData['volume'] ?? 0);
                        $hJ = (float) ($jasaData['harga'] ?? 0);
                        $offer->jasaItems()->create([
                            'nama_jasa'    => $jasaData['nama'],
                            'volume'       => $vJ,
                            'satuan'       => $jasaData['satuan'] ?? 'Ls',
                            'harga_satuan' => $hJ,
                            'harga_jasa'   => $vJ * $hJ,
                        ]);
                    }
                }
            }

            DB::commit();
            // Redirect ke histori
            return redirect()->route('histori.index')->with('success', 'Surat Penawaran Proyek berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}