<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index()
    {
        $clients = Client::orderBy('nama_klien', 'asc')->get();
        return response()->json($clients);
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_klien'     => 'required|string|max:255',
            'client_details' => 'nullable|string',
            'email'          => 'nullable|email|max:255',
            'telepon'        => 'nullable|string|max:100',
            'alamat'         => 'nullable|string',
        ]);

        $client = Client::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Klien berhasil ditambahkan!',
                'client'  => $client,
            ]);
        }

        return redirect()->back()->with('success', 'Klien berhasil ditambahkan!');
    }
}
