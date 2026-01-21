<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Services\KategoriService;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    protected $kategori;

    public function __construct(KategoriService $kategori)
    {
        $this->kategori = $kategori;
    }

    public function index()
    {
        return view('kategori.index', ['data' => Kategori::showData()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->kategori->tambah($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Kategori::showData($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // return Provider::showData($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->kategori->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->kategori->hapus($id);
    }
}
